<?php

namespace Faber\Core\Mail;

use Faber\Core\Contracts\Mail\Mail as IMail;
use Faber\Core\Contracts\Mail\MailDriver;
use Faber\Core\DI\Container;
use Faber\Core\Response\Response;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;

class Mail implements IMail
{
    protected $transport;
    protected Swift_Mailer $mailer;
    protected array $to = [];
    protected array $from = [];
    protected string $subject = '';
    protected string $body = '';
    protected ?Swift_Message $message = null;

    protected function setDefault()
    {
        $config = config('mail.from');
        $this->to = [$config['address'] => $config['name']];
        $this->from = [$config['address'] => $config['name']];
    }

    protected function getMessage(): Swift_Message
    {
        return $this->message ?? (new Swift_Message($this->subject))
                ->setFrom($this->from)
                ->setTo($this->to)
                ->setBody($this->body);
    }

    public function __construct()
    {
        $this->transport = Container::getInstance()->get(MailDriver::class)->getTransport();
        $this->mailer = new Swift_Mailer($this->transport);
        $this->setDefault();
    }

    public function to(array $to): static
    {
        $this->to = $to;
        return $this;
    }

    public function from(array $from): static
    {
        $this->from = $from;
        return $this;
    }

    public function subject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    public function body(string $body): static
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @throws \Faber\Core\Exceptions\TemplateRender
     */
    public function view(string $path, array $params = [], ?string $folderPath = null): static
    {
        $this->body = Container::getInstance()->get(Response::class)->render($path, $params, $folderPath);
        $this->message = $this->getMessage();
        $this->message->setContentType("text/html")->setCharset('utf-8');
        return $this;
    }

    public function attach(string|array $files): static
    {
        $this->message = $this->getMessage();
        if (is_string($files)) {
            $this->message->attach(Swift_Attachment::fromPath($files));
        } else {
            foreach ($files as $file) {
                $this->message->attach(Swift_Attachment::fromPath($file));
            }
        }

        return $this;
    }

    public function send(): int
    {
        return $this->mailer->send($this->getMessage());
    }
}
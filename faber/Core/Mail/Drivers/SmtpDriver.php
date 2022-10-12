<?php

namespace Faber\Core\Mail\Drivers;

use Faber\Core\Contracts\Mail\MailDriver;
use Swift_SmtpTransport;

class SmtpDriver implements MailDriver
{
    public function getTransport()
    {
        $config = config('mail.mailers.smtp');

        $transport = new Swift_SmtpTransport($config['host'], $config['port']);

        if (isset($config['encryption'])) {
            $transport->setEncryption($config['encryption']);
        }

        if (isset($config['username'])) {
            $transport->setUsername($config['username']);

            $transport->setPassword($config['password']);
        }
        return $transport;
    }
}
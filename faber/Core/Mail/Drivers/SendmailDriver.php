<?php

namespace Faber\Core\Mail\Drivers;

use Faber\Core\Contracts\Mail\MailDriver;
use Swift_SendmailTransport;

class SendmailDriver implements MailDriver
{
    public function getTransport()
    {
        return new Swift_SendmailTransport(config('mail.mailers.sendmail.path'));
    }
}
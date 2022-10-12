<?php

namespace Faber\Core\Contracts\Mail;

interface MailDriver
{
    public function getTransport();
}
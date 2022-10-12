<?php

namespace Faber\Core\Mail;

use Faber\Core\Contracts\Mail\MailDriver;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Mail\Drivers\SendmailDriver;
use Faber\Core\Mail\Drivers\SmtpDriver;

class DriverFactory
{
    public static function create(string $driverName): MailDriver
    {
        $reflection = Container::getInstance()->get(Reflection::class);
        return $reflection->createObject(match ($driverName) {
            'sendmail' => SendmailDriver::class,
            default => SmtpDriver::class,
        });
    }
}
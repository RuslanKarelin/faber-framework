<?php

namespace Faber\Core\Providers;

use Faber\Core\Contracts\Mail\Mail as IMail;
use Faber\Core\Contracts\Mail\MailDriver;
use Faber\Core\Mail\DriverFactory;
use Faber\Core\Mail\Mail;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(IMail::class, Mail::class);
        $this->container->singleton(MailDriver::class, DriverFactory::create(config('mail.default')));
        $this->container->bind('Mail', IMail::class);
    }
}
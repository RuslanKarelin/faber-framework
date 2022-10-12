<?php

namespace Faber\Core\Contracts\Mail;

interface Mail
{
    public function to(array $to): static;
    public function from(array $from): static;
    public function subject(string $subject): static;
    public function body(string $body): static;
    public function view(string $path, array $params = [], ?string $folderPath = null): static;
    public function attach(string|array $files): static;
    public function send(): int;
}
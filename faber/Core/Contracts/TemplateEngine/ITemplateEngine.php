<?php

namespace Faber\Core\Contracts\TemplateEngine;

interface ITemplateEngine {
    public function getFileExtension(): string;
    public function render(string $name, array $params = []): string;
    public function init(): ITemplateEngine;
}
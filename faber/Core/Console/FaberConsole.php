<?php

namespace Faber\Core\Console;

use Dotenv\Dotenv;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Enums\App;
use Faber\Core\Filesystem\Finder;
use Faber\Core\Helpers\Str;
use Faber\Core\Providers\ServiceProvider;
use Faber\Core\Exceptions\ConsoleCommandException;
use Faber\Core\Contracts\Filesystem\Filesystem;

require_once dirname(__DIR__) . '/Helpers/Global.php';

class FaberConsole
{
    protected array $commands = [];
    protected Reflection $reflection;
    protected Filesystem $filesystem;

    protected function getCommands(): array
    {
        $appCommandsPath = root_path('app/Console/Commands');
        return array_merge(
            (new Finder())->path(dirname(__DIR__) . '/Console/Commands')->recursive()->getFiles(),
            $this->filesystem->exist($appCommandsPath) ?
                (new Finder())->path($appCommandsPath)->recursive()->getFiles() : []
        );
    }

    protected function prepareNamespace(string $folderName, array $pathInfo): string
    {
        $dirName = explode($folderName, $pathInfo['dirname']);
        return Str::ucfirst($folderName) .
            str_replace('/', '\\', $dirName[1]) . '\\' . $pathInfo['filename'];
    }

    protected function getNamespace(array $pathInfo): ?string
    {
        $namespace = null;
        if (str_contains($pathInfo['dirname'], App::FRAMEWORK_FOLDER)) {
            $namespace = $this->prepareNamespace(App::FRAMEWORK_FOLDER, $pathInfo);
        } elseif (str_contains($pathInfo['dirname'], App::APP_FOLDER)) {
            $namespace = $this->prepareNamespace(App::APP_FOLDER, $pathInfo);
        }
        return $namespace;
    }

    protected function load(): static
    {
        foreach ($this->getCommands() as $command) {
            $namespace = $this->getNamespace(pathinfo($command));
            if (!$namespace) continue;
            $signature = $namespace::getSignature();
            [$commandName, $commandArguments] = SignatureParse::parse($signature);
            $this->commands[$commandName] = [
                'signature' => $signature,
                'arguments' => $commandArguments,
                'namespace' => $namespace,
            ];
        }
        return $this;
    }

    protected function runCommand(array $argv): void
    {
        [$commandName, $commandArguments] = InputParser::parse($argv);
        if (isset($this->commands[$commandName])) {
            $command = $this->commands[$commandName];
            $commandArguments = array_combine($command['arguments'], $commandArguments);
            $commandObject = $this->reflection->createObject($command['namespace']);
            $commandObject->setArguments($commandArguments);
            $this->reflection->handleMethod($commandObject, 'handle');
        } else {
            throw new ConsoleCommandException("Console command {$commandName} not found");
        }
    }

    public function __construct()
    {
        Dotenv::createImmutable(root_path())->safeLoad();
        (new ServiceProvider())->handle();
        $this->reflection = Container::getInstance()->get(Reflection::class);
        $this->filesystem = $this->reflection->createObject(Filesystem::class);
    }

    /**
     * @throws ConsoleCommandException
     */
    public function run(array $argv): void
    {
        $this->load()->runCommand($argv);
    }
}
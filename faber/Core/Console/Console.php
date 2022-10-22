<?php

namespace Faber\Core\Console;

use Dotenv\Dotenv;
use Faber\Core\DI\Container;
use Faber\Core\DI\Reflection;
use Faber\Core\Providers\ServiceProvider;
use Faber\Core\Exceptions\ConsoleCommandException;

require_once dirname(__DIR__) . '/Helpers/Global.php';

class Console
{
    protected array $commands = [];
    protected Reflection $reflection;
    protected Helper $helper;

    protected function load(): static
    {
        foreach ($this->helper->getCommands() as $command) {
            $namespace = $this->helper->getNamespace(pathinfo($command));
            if (!$namespace) continue;
            $signature = $namespace::getSignature();
            [$commandName, $commandArguments, $optionalParameters] = SignatureParse::parse($signature);
            $this->commands[$commandName] = [
                'signature' => $signature,
                'arguments' => $commandArguments,
                'namespace' => $namespace,
                'optionalParameters' => $optionalParameters,
            ];
        }
        return $this;
    }

    protected function clearArguments(array $command, array $commandArguments): array
    {
        foreach ($command['optionalParameters'] as $parameter) {
            if (!array_key_exists($parameter, $commandArguments)) {
                unset($command['arguments'][array_search($parameter, $command['arguments'])]);
            }
        }
        return $command;
    }

    protected function runCommand(array $argv): void
    {
        [$commandName, $commandArguments] = InputParser::parse($argv);
        if (isset($this->commands[$commandName])) {
            $command = $this->clearArguments($this->commands[$commandName], $commandArguments);
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
        $this->helper = $this->reflection->createObject(Helper::class);
    }

    /**
     * @throws ConsoleCommandException
     */
    public function run(array $argv): void
    {
        $this->load()->runCommand($argv);
    }
}
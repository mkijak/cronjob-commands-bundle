<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Runner;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;

/**
 * "In comparison with a direct call from the console,
 * calling a command from a controller has a slight performance impact because of the request stack overhead."
 * @see https://symfony.com/doc/current/console/command_in_controller.html
 */
final class CommandRunner
{
    /**
     * @var Application
     */
    private $console;

    public function __construct()
    {
        $this->console = new Application();
        $this->console->setAutoExit(false);
    }

    /**
     * @param string $commandClass  Fully-qualified class name of a command
     * @param string $commandName   your:command:name
     * @param array $arguments      ['argument-name' => 'argument-value', 'argument2-name' => 'argument2-value']
     * @param array $options        ['--option-name' => 'option-value', '--option2-name' => 'option2-value']
     *
     * @throws InvalidConfigurationException
     *
     * @return void
     */
    public function run(string $commandClass, string $commandName, array $arguments = [], array $options = [], OutputInterface $output)
    {
        if (!class_exists($commandClass)) {
            throw new InvalidConfigurationException(
                sprintf('Cannot instantiate class [%s]. Remember to provide a fully-qualified class name.', $commandClass)
            );
        }

        $this->console->add(new $commandClass());

        $commandParamPart = ['command' => $commandName];
        $params = array_merge($commandParamPart, $arguments, $options);

        $input = new ArrayInput($params);

        $this->console->run($input, $output);
    }
}

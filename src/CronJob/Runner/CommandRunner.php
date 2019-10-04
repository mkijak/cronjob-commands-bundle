<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Runner;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;
use Symfony\Component\HttpKernel\KernelInterface;

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

    public function __construct(KernelInterface $kernel)
    {
        $this->console = new Application($kernel);
        $this->console->setAutoExit(false);
    }

    /**
     * @param string          $commandName  your:command:name
     * @param array           $arguments    ['argument-name' => 'argument-value', 'argument2-name' => 'argument2-value']
     * @param array           $options      ['--option-name' => 'option-value', '--option2-name' => 'option2-value']
     * @param OutputInterface $output       For output
     *
     * @return void
     */
    public function run(string $commandName, array $arguments = [], array $options = [], OutputInterface $output = null)
    {
        $commandParamPart = ['command' => $commandName];
        $params = array_merge($commandParamPart, $arguments, $options);

        $input = new ArrayInput($params);

        $this->console->run($input, $output);
    }
}

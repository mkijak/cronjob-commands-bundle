<?php

namespace Mkijak\CronJobCommandsBundle\CronJob;

use Mkijak\CronJobCommandsBundle\CronJob\Config\Command;
use Mkijak\CronJobCommandsBundle\CronJob\Config\CommandArgument;
use Mkijak\CronJobCommandsBundle\CronJob\Config\CommandOption;
use Mkijak\CronJobCommandsBundle\CronJob\Config\Config;
use Mkijak\CronJobCommandsBundle\CronJob\Runner\CommandRunner;
use Mkijak\CronJobCommandsBundle\CronJob\Schedule\CommandSchedule;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class CronJobCommands
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var CommandSchedule
     */
    private $commandSchedule;
    /**
     * @var CommandRunner
     */
    private $commandRunner;
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param Config $config
     * @param CommandSchedule $commandSchedule
     * @param CommandRunner $commandRunner
     */
    public function __construct(Config $config, CommandSchedule $commandSchedule, CommandRunner $commandRunner)
    {
        $this->config = $config;
        $this->commandSchedule = $commandSchedule;
        $this->commandRunner = $commandRunner;
        $this->output = new NullOutput();
    }

    /**
     * @param \DateTime|null $timeToCheckAgainst     By default 'now' but you can set any time you want
     * @param OutputInterface|null $output           If set to null DummyOutput will be used -
     *                                               you won't even see exceptions thrown by the commands
     */
    public function runCommands(\DateTime $timeToCheckAgainst = null, OutputInterface $output = null)
    {
        if ($output) {
            $this->output = $output;
        }

        $time = $timeToCheckAgainst ?: new \DateTime('now', new \DateTimeZone($this->config->timezone()));

        $this->output->writeln(PHP_EOL . sprintf('<info>CronJobCommands</info>' . PHP_EOL));
        $this->output->writeln(sprintf('Time (%s): %s',
            $this->config->timezone(), $time->format('Y-m-d H:i:s')));

        foreach ($this->commandSchedule->loadCommandsToRun($time, $this->output) as $command) {
            $this->runCommand($command);
        }
    }

    private function runCommand(Command $command)
    {
        $this->output->writeln('');
        $this->output->writeln(sprintf('<bg=yellow;options=bold>%s</>', $command->getName()));

        $argsAsArray = $optsAsArray = $multivOptsAsArray = [];

        /** @var CommandArgument $argument */
        foreach ($command->getArguments() as $argument) {
            $argsAsArray[$argument->getName()] = $argument->getValue();
        }

        /** @var CommandOption $option */
        foreach ($command->getOptions() as $option) {
            $optsAsArray[$option->getNameWithMinusPrefix()] = $option->getValue();
        }

        /** @var CommandOption $option */
        foreach ($command->getMultivalueOptions() as $option) {
            $multivOptsAsArray[$option->getNameWithMinusPrefix()] = $option->getValue();
        }

        try {
            $this->commandRunner->run($command->getName(), $argsAsArray, $optsAsArray, $multivOptsAsArray, $this->output);
        } catch (\Exception $exception) {
            $this->output->writeln(
                sprintf('<error>Exception [%s]: %s</error>', get_class($exception), $exception->getMessage()),
                OutputInterface::VERBOSITY_QUIET
            );

            $this->output->writeln($exception->getTraceAsString(), OutputInterface::VERBOSITY_QUIET);
        }
    }
}

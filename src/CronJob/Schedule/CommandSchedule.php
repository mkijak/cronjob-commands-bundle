<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Schedule;

use Mkijak\CronJobCommandsBundle\CronJob\Config\Command;
use Mkijak\CronJobCommandsBundle\CronJob\Config\Config;
use Symfony\Component\Console\Output\OutputInterface;
use Cron\CronExpression;

final class CommandSchedule
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function loadCommandsToRun(\DateTime $time, OutputInterface $output)
    {
        $this->output = $output;

        $cmds = [];

        foreach ($this->config->loadCommands() as $command) {
            if ($this->isDue($command, $time)) {
                $cmds[] = $command;
            }
        }

        return $cmds;
    }

    private function isDue(Command $command, \DateTime $time)
    {
        if (!CronExpression::isValidExpression($command->getCronExpr())) {
            $this->output->writeln(sprintf('<error>[%s] is not a valid cron expression (%s)</error>',
                $command->getCronExpr(), $command->getName()), OutputInterface::VERBOSITY_QUIET);

            return false;
        }

        $exprInterpreter = CronExpression::factory($command->getCronExpr());

        if (!$exprInterpreter->isDue($time)) {
            $this->output->writeln(sprintf('%s will be skipped [%s]', $command->getName(), $command->getCronExpr()));

            return false;
        }

        $this->output->writeln(sprintf('%s will be ran [%s]', $command->getName(), $command->getCronExpr()));

        return true;
    }
}

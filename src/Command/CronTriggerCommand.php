<?php

namespace Mkijak\CronJobCommandsBundle\Command;

use Mkijak\CronJobCommandsBundle\CronJob\CronJobCommands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CronTriggerCommand extends Command
{
    /**
     * @var CronJobCommands
     */
    private $cron;

    /**
     * @param CronJobCommands $cron
     */
    public function __construct(CronJobCommands $cron)
    {
        parent::__construct(null);

        $this->cron = $cron;
    }

    protected function configure()
    {
        $this
            ->setDescription('Processes command schedule and runs commands according to configuration')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cron->runCommands(null, $output);

        return 0;
    }
}

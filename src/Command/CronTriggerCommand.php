<?php

namespace Mkijak\CronJobCommandsBundle\Command;

use Mkijak\CronJobCommandsBundle\CronJob\CronJobCommands;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CronTriggerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setDescription('Processes command schedule and runs commands according to configuration')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get(CronJobCommands::class)->runCommands(null, $output);
    }
}

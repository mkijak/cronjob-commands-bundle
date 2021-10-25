<?php

namespace Mkijak\CronJobCommandsBundle\Command;

use Mkijak\CronJobCommandsBundle\CronJob\CronJobCommands;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CronTriggerCommand extends Command
{
    use LockableTrait;

    /**
     * @var CronJobCommands
     */
    private $cron;
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @param CronJobCommands $cron
     * @param LoggerInterface|null $logger
     */
    public function __construct(CronJobCommands $cron, ?LoggerInterface $logger = null)
    {
        parent::__construct(null);

        $this->cron = $cron;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setDescription('Processes command schedule and runs commands according to configuration')
            ->addOption('lock', null, InputOption::VALUE_NONE, 'Prevents from running multiple instances')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($lockOthers = $input->getOption('lock')) {
            if(!$this->lock()) {
                if($this->logger) {
                    $this->logger->info('Cron job already running, skipping current process');
                }

                $output->writeln('Cron job already running, skipping current process');

                return 0;
            }
        }

        $id = time();

        if($this->logger) {
            $this->logger->info(sprintf('Running cron job [%d]', $id));
        }

        $this->cron->runCommands(null, $output);

        if($this->logger) {
            $this->logger->info(sprintf('Cron job finished [%d]', $id));
        }

        if($lockOthers) {
            $this->release();
        }

        return 0;
    }
}

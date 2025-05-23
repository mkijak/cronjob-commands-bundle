<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Config;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

final class Config
{
    public function __construct(
        private ?string $timezone = null,
        private array $commands = [],
    ) {
        $this->validateConfig();
    }

    /**
     * @return string
     */
    public function timezone(): string
    {
        return $this->timezone ?: 'UTC';
    }

    /**
     * @return array|Command[]
     */
    public function loadCommands(): array
    {
        return array_map(function(array $command) {
            return $this->prepareCommand($command);
        }, $this->commands);
    }

    /**
     * @param array $array
     *
     * @return Command
     */
    private function prepareCommand(array $array): Command
    {
        $args = $opts = $multiVOpts = [];

        foreach ($array['arguments'] as $name => $value) {
            $args[] = new CommandArgument($name, $value);
        }

        foreach ($array['options'] as $name => $value) {
            $opts[] = new CommandOption($name, $value);
        }

        foreach ($array['multivalue_options'] as $name => $value) {
            $multiVOpts[] = new CommandOption($name, $value);
        }

        return new Command($array['name'], $array['cron_expression'], $args, $opts, $multiVOpts, $array['enabled']);
    }

    private function validateConfig(): void
    {
        try {
            new \DateTimeZone($this->timezone());
        } catch (\Exception $exception) {
            throw new InvalidConfigurationException('Invalid timezone provided in config - please use timezone string supported by DateTimezone constructor: https://secure.php.net/manual/class.datetimezone.php');
        }
    }
}

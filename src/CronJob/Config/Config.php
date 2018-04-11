<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Config;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

final class Config
{
    /**
     * @var string
     */
    private $timezone;
    /**
     * @var array
     */
    private $commands;

    /**
     * @param string $timezone
     * @param array $commands
     */
    public function __construct(string $timezone = null, array $commands = [])
    {
        $this->timezone = $timezone;
        $this->commands = $commands;

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
        $args = $opts = [];

        foreach ($array['arguments'] as $name => $value) {
            $args[] = new CommandArgument($name, $value);;
        }

        foreach ($array['options'] as $name => $value) {
            $opts[] = new CommandOption($name, $value);;
        }

        return new Command($array['name'], $array['cron_expression'], $args, $opts);
    }

    private function validateConfig()
    {
        try {
            new \DateTimeZone($this->timezone());
        } catch (\Exception $exception) {
            throw new InvalidConfigurationException('Invalid timezone provided in config - please use timezone string supported by DateTimezone constructor: https://secure.php.net/manual/class.datetimezone.php');
        }
    }
}

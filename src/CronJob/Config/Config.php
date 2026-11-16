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

        foreach ($this->commands as $commandKey => $command) {
            foreach ($command['options'] ?? [] as $name => $value) {
                if ($value !== null && !is_scalar($value) && !$this->isArrayOfScalars($value)) {
                    throw new InvalidConfigurationException(sprintf(
                        'Invalid value of option "%s" of command "%s" - expected a scalar or an array of scalar values, got %s',
                        $name,
                        $commandKey,
                        json_encode($value),
                    ));
                }
            }

            if (!empty($command['multivalue_options'])) {
                @trigger_error(sprintf(
                    'Using "multivalue_options" (command "%s") is deprecated and will be removed in '
                    . 'mkijak/cronjob-commands-bundle 3.0, put arrays of values directly in "options" instead.',
                    $commandKey,
                ), E_USER_DEPRECATED);
            }

            foreach ($command['multivalue_options'] ?? [] as $name => $values) {
                if (!$this->isArrayOfScalars($values)) {
                    throw new InvalidConfigurationException(sprintf(
                        'Invalid value of multivalue option "%s" of command "%s" - expected an array of scalar values, got %s',
                        $name,
                        $commandKey,
                        json_encode($values),
                    ));
                }
            }
        }
    }

    private function isArrayOfScalars(mixed $value): bool
    {
        return is_array($value) && !array_filter($value, fn($v) => !is_scalar($v));
    }
}

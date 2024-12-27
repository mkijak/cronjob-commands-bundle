<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Config;

final class Command
{
    /**
     * @param string $name
     * @param string $cronExpr
     * @param array|CommandArgument[] $arguments
     * @param array|CommandOption[] $options
     * @param array|CommandOption[] $multivalueOptions
     * @param bool $enabled
     */
    public function __construct(
        private string $name,
        private string $cronExpr,
        private array $arguments,
        private array $options,
        private array $multivalueOptions,
        private bool $enabled,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCronExpr(): string
    {
        return $this->cronExpr;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return array|CommandOption[]
     */
    public function getMultivalueOptions()
    {
        return $this->multivalueOptions;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}

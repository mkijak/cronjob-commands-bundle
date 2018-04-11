<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Config;

final class Command
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $cronExpr;
    /**
     * @var array|CommandArgument[]
     */
    private $arguments;
    /**
     * @var array|CommandOption[]
     */
    private $options;

    /**
     * @param string $name
     * @param string $cronExpr
     * @param array|CommandArgument[] $arguments
     * @param array|CommandOption[] $options
     */
    public function __construct(string $name, string $cronExpr, array $arguments, array $options)
    {
        $this->name = $name;
        $this->cronExpr = $cronExpr;
        $this->arguments = $arguments;
        $this->options = $options;
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
}

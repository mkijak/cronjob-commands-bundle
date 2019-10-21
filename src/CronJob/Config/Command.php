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
     * @var array|CommandOption[]
     */
    private $multivalueOptions;

    /**
     * @param string $name
     * @param string $cronExpr
     * @param array|CommandArgument[] $arguments
     * @param array|CommandOption[] $options
     * @param array|CommandOption[] $multivalueOptions
     */
    public function __construct(string $name, string $cronExpr, array $arguments, array $options, array $multivalueOptions)
    {
        $this->name = $name;
        $this->cronExpr = $cronExpr;
        $this->arguments = $arguments;
        $this->options = $options;
        $this->multivalueOptions = $multivalueOptions;
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
}

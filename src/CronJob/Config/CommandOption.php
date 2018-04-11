<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Config;

final class CommandOption
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getNameWithMinusPrefix(): string
    {
        return sprintf('--%s', $this->name);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}

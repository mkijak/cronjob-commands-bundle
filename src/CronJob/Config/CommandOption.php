<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Config;

final class CommandOption
{
    public function __construct(private string $name, private mixed $value)
    {
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
    public function getValue(): mixed
    {
        return $this->value;
    }
}

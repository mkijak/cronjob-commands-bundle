<?php

namespace Mkijak\CronJobCommandsBundle\CronJob\Config;

final class CommandArgument
{
    public function __construct(private string $name, private mixed $value)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}

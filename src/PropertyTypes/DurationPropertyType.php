<?php

namespace Spatie\IcalendarGenerator\PropertyTypes;

final class DurationPropertyType extends PropertyType
{
    /** @var int */
    private $minutes;

    public function __construct(string $name, int $minutes)
    {
        $this->name = $name;

        $this->minutes = $minutes;

        $this->parameters = [new Parameter('VALUE', 'DURATION')];
    }

    public function getValue(): string
    {
        return "PT{$this->minutes}M";
    }

    public function getOriginalValue() : int
    {
        return $this->minutes;
    }
}

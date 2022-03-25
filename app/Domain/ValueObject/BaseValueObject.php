<?php

namespace App\Domain\ValueObject;

abstract class BaseValueObject
{
    use ValidatableValueObjectTrait;

    protected $value;

    public function __construct($value)
    {
        self::validate($value);
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}

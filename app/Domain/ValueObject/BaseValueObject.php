<?php

namespace App\Domain\ValueObject;

abstract class BaseValueObject
{
    use ValidatableValueObjectTrait;

    protected static string $name = "";

    /**
     * @param $value
     */
    public function __construct($value)
    {
        self::validate($value);
    }

    public function __toString():string{
        return $this->value;
    }
}

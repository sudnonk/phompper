<?php

namespace App\Domain\ValueObject;

abstract class BaseValueObject
{
    use ValidatableValueObjectTrait;

    protected static string $name = "";

    public function __construct(public readonly mixed $value)
    {
        self::validate($value);
    }
}

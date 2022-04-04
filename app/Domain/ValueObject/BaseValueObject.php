<?php

namespace App\Domain\ValueObject;

abstract class BaseValueObject
{
    use ValidatableValueObjectTrait;

    public function __construct(public readonly mixed $value)
    {
        self::validate($value);
    }
}

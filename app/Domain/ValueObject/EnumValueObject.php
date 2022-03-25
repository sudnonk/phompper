<?php

namespace App\Domain\ValueObject;

use App\Domain\Rules\EnumValueRule;
use MyCLabs\Enum\Enum;

abstract class EnumValueObject extends Enum
{
    use ValidatableValueObjectTrait;

    public function __construct($value)
    {
        self::validate($value);
        parent::__construct($value);
    }

    public static function rule(): array
    {
        return [new EnumValueRule(static::$name, static::class)];
    }
}

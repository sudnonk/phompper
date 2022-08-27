<?php

namespace App\Domain\ValueObject\Position;

use App\Domain\Rules\UuidRule;
use App\Domain\ValueObject\BaseValueObject;
use App\Domain\ValueObject\Uuid;

/**
 * 地点IDはUUIDv6
 */
class PositionId extends BaseValueObject
{
    protected static string $name = "地点ID";

    public function __construct(public readonly Uuid $value)
    {
        parent::__construct($value);
    }

    public static function rule(): array
    {
        return [new UuidRule()];
    }

}

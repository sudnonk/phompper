<?php

namespace App\Domain\ValueObject\Position;

use App\Domain\Rules\UuidRule;
use App\Domain\ValueObject\BaseValueObject;
use App\Domain\ValueObject\Uuid;

/**
 * 地点IDはUUIDv6
 */
final class PositionDetailId extends BaseValueObject
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

    public static function generate(): self
    {
        return new self(Uuid::generate());
    }

    public static function fromString(string $vale): self
    {
        $uuid = new Uuid($vale);
        return new self($uuid);
    }
}

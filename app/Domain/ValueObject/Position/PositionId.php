<?php

namespace App\Domain\ValueObject\Position;

use App\Domain\Rules\PositionIdRule;
use App\Domain\ValueObject\BaseValueObject;
use Ramsey\Uuid\Uuid;

/**
 * 地点IDはUUIDv6
 */
class PositionId extends BaseValueObject
{
    protected static string $name = "地点ID";

    public function __construct(public readonly string $value)
    {
        parent::__construct($value);
    }

    public static function rule(): array
    {
        return [new PositionIdRule()];
    }

    /**
     * UUIDを生成してPositionIdを返す
     *
     * @return static
     */
    public static function generate(): self
    {
        $uuid = Uuid::uuid6()->toString();
        return new self($uuid);
    }

    /**
     * 渡された文字列がUUIDとして正しければtrue、それ以外はfalse
     * UUIDライブラリを変えたときに変更箇所を減らすためのラッパー
     *
     * @param string $UUIDlike
     * @return bool
     */
    public static function isValid(string $UUIDlike): bool
    {
        return Uuid::isValid($UUIDlike);
    }
}

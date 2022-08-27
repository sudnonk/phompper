<?php

namespace App\Domain\ValueObject;

class Uuid extends BaseValueObject
{
    public function __construct(public readonly string $value)
    {
        parent::__construct($value);
    }

    /**
     * UUIDを生成してPositionIdを返す
     *
     * @return static
     */
    public static function generate(): self
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid6()->toString();
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
        return \Ramsey\Uuid\Uuid::isValid($UUIDlike);
    }
}

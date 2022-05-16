<?php

namespace App\Domain\ValueObject;

class DateTimeImmutable extends BaseValueObject
{
    public function __construct(public readonly \DateTimeImmutable $value)
    {
        parent::__construct($value);
    }

    public function getAsFormat(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->value->format($format);
    }

    public static function now(): self
    {
        return new self(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Tokyo')));
    }

    public function __toString(): string
    {
        return $this->getAsFormat();
    }
}

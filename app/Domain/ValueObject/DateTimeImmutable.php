<?php

namespace App\Domain\ValueObject;

class DateTimeImmutable extends BaseValueObject
{
    /**
     * @param DateTimeImmutable $value
     */
    public function __construct(public readonly mixed $value)
    {
        parent::__construct($this->value);
    }

    public function getAsFormat(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->value->getAsFormat($format);
    }

    public static function now(): self
    {
        return new self(new \DateTimeImmutable('now', 'Asia/Tokyo'));
    }
}

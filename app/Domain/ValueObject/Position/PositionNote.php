<?php

namespace App\Domain\ValueObject\Position;

use App\Domain\ValueObject\BaseValueObject;

final class PositionNote extends BaseValueObject
{
    protected static string $name = "備考";

    public function __construct(mixed $value)
    {
        if ($value === null) {
            $value = "";
        }
        parent::__construct($value);
    }


    public static function rule(): array
    {
        return ['nullable', 'string', 'max:65535'];
    }

    public static function message(): array
    {
        return [':attributeは:max文字以下にしてください。'];
    }

    public function getLength(): int
    {
        return mb_strlen($this->value);
    }
}

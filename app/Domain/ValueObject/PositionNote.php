<?php

namespace App\Domain\ValueObject;

final class PositionNote extends BaseValueObject
{
    protected static $name = "備考";

    public static function rule(): array
    {
        return ['nullable','string','max:65535'];
    }

    public static function message(): array
    {
        return [':attributeは:max文字以下にしてください。'];
    }
}

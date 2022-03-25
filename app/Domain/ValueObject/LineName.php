<?php

namespace App\Domain\ValueObject;

class LineName extends BaseValueObject
{
    protected static $name = "線名";

    public static function rule(): array
    {
        return ['string','min:1','max:255'];
    }

    public static function message(): array
    {
        return [':attributeは:min文字以上:max文字以下にしてください。'];
    }
}

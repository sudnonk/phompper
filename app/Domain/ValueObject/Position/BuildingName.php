<?php

namespace App\Domain\ValueObject\Position;

use App\Domain\ValueObject\BaseValueObject;

final class BuildingName extends BaseValueObject
{
    protected static string $name = "通信ビル名";

    public function __construct(public readonly string $value)
    {
        parent::__construct($value);
    }

    public static function rule(): array
    {
        return ['string','min:1','max:255'];
    }

    public static function message(): array
    {
        return [":attributeは:min文字以上:max文字以下にしてください。"];
    }

}

<?php

namespace App\Domain\ValueObject\Position;

use App\Domain\ValueObject\BaseValueObject;

final class BuildingName extends BaseValueObject
{
    protected static string $name = "通信ビル名";

    /**
     * @param string $value
     */
    public function __construct(public readonly mixed $value)
    {
        parent::__construct($this->value);
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

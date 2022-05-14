<?php

namespace App\Domain\ValueObject;

use App\Exceptions\ValidatorInvalidArgumentException;
use Illuminate\Validation\Rule;

enum PositionType: string
{
    use ValidatableValueObjectTrait;

    case DENSHIN = "電信柱";
    case DENCHU = "電柱";
    case BUILDING = "通信ビル";
    case OTHER = "その他";

    public function equals(self $value): bool
    {
        return $this->value === $value->value;
    }

    public static function rule(): array
    {
        return [(string)Rule::in(self::cases())];
    }

    /**
     * @param string $value
     * @return PositionType
     * @throws ValidatorInvalidArgumentException
     */
    public static function tryFromString(string $value): PositionType
    {
        PositionType::validate($value,"地点種別");
        return self::tryFrom($value);
    }
}

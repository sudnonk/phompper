<?php

namespace App\Rules;

use App\Models\PositionTypes;
use Illuminate\Contracts\Validation\Rule;

class PositionNameRule implements Rule
{
    protected $position_type;

    public function __construct(?string $position_type)
    {
        assert(PositionTypes::isValidKey($position_type));
        $this->position_type = $position_type;
    }

    public function passes($attribute, $value): bool
    {
        switch (true) {
            //地点種別が電柱化電信柱ならビル名は必須、それ以外はnull
            case PositionTypes::search("通信ビル") === $this->position_type:
                return is_string($value);
            default:
                return $value === null;
        }
    }

    public function message(): string
    {
        return '地点種別が通信ビルのときはビル名が必須です。';
    }

}

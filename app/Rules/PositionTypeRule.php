<?php

namespace App\Rules;

use App\Models\PositionTypes;
use Illuminate\Contracts\Validation\Rule;

class PositionTypeRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return PositionTypes::isValidKey($value);
    }

    public function message(): string
    {
        return "地点種別を選択してください。";
    }

}

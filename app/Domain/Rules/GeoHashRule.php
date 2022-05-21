<?php

namespace App\Domain\Rules;

use App\Domain\ValueObject\Position\GeoHash;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;

class GeoHashRule implements Rule, ImplicitRule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $expression = sprintf('/^([0-9]|[b-h]|[j-k]|[m-n]|[p-z]){%d}$/', GeoHash::PRECISION);
        if (preg_match($expression, $value) !== 1) {
            return false;
        }
        try {
            GeoHash::parse($value);
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return ":inputはGeoHashの形式を満たしていません。";
    }

}

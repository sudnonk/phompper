<?php

namespace App\Domain\Rules;

use App\Domain\ValueObject\Position\PositionId;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;

class UuidRule implements Rule, ImplicitRule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return PositionId::isValid($value);
    }

    public function message(): string
    {
        return ':inputは正しい地点IDでは有りません。';
    }
}

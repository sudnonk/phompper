<?php

namespace App\Domain\Rules;

use App\Domain\ValueObject\Uuid;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;

class UuidRule implements Rule, ImplicitRule
{
    public function passes($attribute, $value): bool
    {
        if ($value instanceof Uuid) {
            $value = $value->value;
        }
        if (!is_string($value)) {
            return false;
        }

        return Uuid::isValid($value);
    }

    public function message(): string
    {
        return ':inputは正しいUUIDでは有りません。';
    }
}

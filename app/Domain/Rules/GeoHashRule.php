<?php

namespace App\Domain\Rules;

use App\Domain\ValueObject\GeoHash;
use Illuminate\Contracts\Validation\Rule;

class GeoHashRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        //GeoHashで使用するBase32では、a,i,l,o以外の小文字のアルファベットと0-9の計32文字を使うらしい
        if(preg_match('/^([0-9]|[b-h]|[j-k]|[m-n]|[p-z])+$/',$value) !== 1){
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
        return ":attributeはGeoHashの形式を満たしていません。";
    }

}

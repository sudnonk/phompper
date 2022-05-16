<?php

namespace App\Http\Requests;

use App\Domain\Rules\GeoHashRule;
use App\Domain\ValueObject\Position\GeoHash;
use Illuminate\Foundation\Http\FormRequest;

class GeoHashParameterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'geoHash' => ['required', new GeoHashRule()],
        ];
    }

    public function getValidatedGeoHash(): GeoHash
    {
        return new GeoHash($this->validated()['geoHash']);
    }
}

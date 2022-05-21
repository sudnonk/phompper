<?php

namespace App\Http\Requests;

use App\Domain\Rules\GeoHashRule;
use App\Domain\ValueObject\Position\GeoHash;
use Illuminate\Foundation\Http\FormRequest;

class GeoHashParameterRequest extends FormRequest
{
    use NoRedirectFormRequestTrait;

    public function rules(): array
    {
        return [
            'geoHash' => ['required', new GeoHashRule()],
        ];
    }

    /**
     * URLのパラメータをFormRequestのバリデーション対象にする
     *
     * @param mixed $keys
     * @return array
     */
    public function all($keys = null)
    {
        return ["geoHash" => $this->route('geoHash')];
    }

    public function getValidatedGeoHash(): GeoHash
    {
        return new GeoHash($this->validated()['geoHash'] ?? '');
    }
}

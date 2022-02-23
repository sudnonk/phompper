<?php

namespace App\Http\Requests;

use App\Models\Position;
use App\Rules\PositionLineRule;
use App\Rules\PositionNameRule;
use App\Rules\PositionNumberRule;
use App\Rules\PositionTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * 地点新規作成時のフォーム入力データバリデーションルール
 */
class StorePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        //ログインしていればOK
        return Auth::user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'long' => ['required', 'numeric', 'between:-180,180'],
            'type' => ['required', new PositionTypeRule()],
            'line' => [
                'nullable',
                new PositionLineRule($this->request->get('type')),
                'string',
                'max:255',
            ],
            'number' => [
                'nullable',
                new PositionNumberRule($this->request->get('type')),
                'string',
                'max:255',
            ],
            'name' => [
                'nullable',
                new PositionNameRule($this->request->get('type')),
                'string',
                'max:255',
            ],
            'note' => [
                'nullable',
                'string',
                'max:1024',
            ],
        ];
    }

    /**
     * バリデーション成功時に行う処理
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        //経度と緯度をgeohashにする
        $geohash = Position::toGeoHash($this->input('lat') + 0.00, $this->input('long') + 0.00);
        $this->merge([
            'geohash' => $geohash,
        ]);
    }
}

<?php

namespace App\Http\Requests;

use App\Domain\Entity\Position\Position;
use App\Domain\Rules\PositionRule;
use App\Domain\ValueObject\PositionType;
use App\Exceptions\ValidatorInvalidArgumentException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;

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
    #[ArrayShape([
        'lat' => "string[]",
        'long' => "string[]",
        'type' => "string[]",
        'line' => "Rule[]",
        'number' => "Rule[]",
        'name' => "Rule[]",
        'note' => "Rule[]",
    ])] public function rules(): array
    {
        $type = PositionType::tryFrom($this->request->get('type'));
        return [
            'lat' => [
                'required',
                PositionRule::latRule(),
            ],
            'long' => [
                'required',
                PositionRule::longRule(),
            ],
            'type' => [
                'required',
                PositionRule::typeRule(),
            ],
            'line' => [
                PositionRule::nameRule($type),
            ],
            'number' => [
                PositionRule::numberRule($type),
            ],
            'name' => [
                PositionRule::buildingRule($type),
            ],
            'note' => [
                PositionRule::noteRule($type),
            ],
        ];
    }

    /**
     * @return Position
     * @throws ValidatorInvalidArgumentException
     */
    public function makePosition(): Position
    {
        $values = $this->validated();
        return Position::fromStrings(
            latitude: $values["lat"],
            longitude: $values['long'],
            positionType: $values['type'],
            lineName: $values['line'],
            lineNumber: $values['number'],
            buildingName: $values['name'],
            positionNote: $values['note']
        );
    }
}

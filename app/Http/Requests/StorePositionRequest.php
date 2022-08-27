<?php

namespace App\Http\Requests;

use App\Domain\Entity\Image\Image;
use App\Domain\Entity\Position\Position;
use App\Domain\Entity\Position\PositionDetail;
use App\Domain\Rules\PositionRule;
use App\Domain\ValueObject\Position\PositionType;
use App\Exceptions\ValidatorInvalidArgumentException;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * 地点新規作成時のフォーム入力データバリデーションルール
 */
class StorePositionRequest extends FormRequest
{
    use NoRedirectFormRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        //ユーザ機能を実装する予定は無いので誰でも登録できる
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'lat' => "string[]",
        'long' => "string[]",
        'details' => [
            'type' => "string[]",
            'line' => "Rule[]",
            'number' => "Rule[]",
            'name' => "Rule[]",
            'note' => "Rule[]",
        ],
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
            'details' => [
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
        $position = Position::fromLatLng(latitude: $values["lat"], longitude: $values["long"]);
        foreach ($values["details"] as $detail) {
            $positionDetail = PositionDetail::createPositionDetailFromString(
                positionid: null,
                geoHash: $position->geoHash,
                positionType: $detail['type'],
                lineName: $detail['line'] ?? null,
                lineNumber: $detail['number'] ?? null,
                buildingName: $detail['name'] ?? null,
                positionNote: $detail['note']
            );
            $position->addPositionDetail($positionDetail);
        }
        return $position;
    }

    /**
     * @param Position $position
     * @return Array<string,Image> キーは一次保存先のtmpPath、値は保存先のImagePath
     * @throws \InvalidArgumentException
     */
    public function makeImages(Position $position): array
    {
        $images = [];
        if ($this->file('images') === null) {
            return $images;
        }
        foreach ($this->file('images') as $file) {
            $tmpPath = $file->getRealPath();
            if ($tmpPath === false) {
                continue;
            }
            $images[$tmpPath] = Image::createFromUploadedFile($position, $file);
        }
        return $images;
    }
}

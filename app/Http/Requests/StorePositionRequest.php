<?php

namespace App\Http\Requests;

use App\Domain\Entity\Image\Image;
use App\Domain\Entity\Position\Position;
use App\Domain\Entity\Position\PositionDetail;
use App\Domain\Rules\PositionRule;
use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\Latitude;
use App\Domain\ValueObject\Position\Longitude;
use App\Domain\ValueObject\Position\PositionType;
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

    public function getGeoHash(): GeoHash
    {
        $values = $this->validated();
        return GeoHash::fromLatLng(new Latitude($values["lat"]), new Longitude($values["long"]));
    }

    /**
     * 入力された値からPositionDetailを作成し、Positionに追加する
     *
     * @param Position $position 追加先のPosition
     * @return Position 追加されたPosition
     */
    public function fillPosition(Position $position): Position
    {
        $values = $this->validated();

        $positionDetail = PositionDetail::createPositionDetailFromString(
            positionid: null,
            geoHash: $position->geoHash,
            positionType: $values['type'],
            lineName: $values['line'] ?? null,
            lineNumber: $values['number'] ?? null,
            buildingName: $values['name'] ?? null,
            positionNote: $values['note']
        );
        $position->addPositionDetail($positionDetail);

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

<?php

namespace App\Http\Resources;

use App\Domain\Entity\Position\BuildingPosition;
use App\Domain\Entity\Position\DenchuPosition;
use App\Domain\Entity\Position\DenshinPosition;
use App\Domain\Entity\Position\OtherPosition;
use App\Domain\Entity\Position\Position;
use App\Domain\ValueObject\Image\ImageURL;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class PositionResource extends JsonResource
{
    /**
     * @param StorePositionRequest|UpdatePositionRequest $request
     * @return array
     */
    #[ArrayShape([
        'geoHash' => "string",
        'latitude' => "string",
        'longitude' => "string",
        'type' => "string",
        'details' => [
            [
                'id' => "string",
                'note' => "string|null",
                'lineNumber' => "string|null",
                'buildingName' => "string|null",
                'lineName' => "string|null",
            ],
        ],
        'imageURLs' => [
            "string",
        ],
    ])] public function toArray($request): array
    {
        /** @var Position $position */
        $position = $this->resource['position'];
        /** @var ImageURL[] $images */
        $images = $this->resource['images'];
        $data = [
            'geoHash' => $position->geoHash->value,
            'latitude' => $position->geoHash->latitude->value,
            'longitude' => $position->geoHash->longitude->value,
            'type' => $position->getPositionType()->value,
            'details' => [],
        ];

        foreach ($position->getPositionDetails() as $positionDetail) {
            $detail = [
                "id" => $positionDetail->positionId->value->value,
                "note" => $positionDetail->positionNote->value,
            ];
            switch (true) {
                case $positionDetail instanceof DenchuPosition:
                case $positionDetail instanceof DenshinPosition:
                    $detail['lineName'] = $positionDetail->lineName->value;
                    $detail['lineNumber'] = $positionDetail->lineNumber->value;
                    break;
                case $positionDetail instanceof BuildingPosition:
                    $detail['buildingName'] = $positionDetail->buildingName->value;
                    break;
                case $positionDetail instanceof OtherPosition:
                    break;
            }
            $data["details"][] = $detail;
        }

        $data['imageURLs'] = [];
        foreach ($images as $image) {
            $data['imageURLs'][] = $image->value;
        }

        return $data;
    }
}

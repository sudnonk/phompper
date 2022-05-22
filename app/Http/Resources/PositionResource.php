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
        'note' => "string|null",
        'imageURLs' => "array",
        'lineNumber' => "string|null",
        'buildingName' => "string|null",
        'lineName' => "string|null",
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
            'type' => $position->type->value,
            'note' => $position->positionNote->value,
        ];
        switch (true) {
            case $position instanceof DenchuPosition:
                $data['lineName'] = $position->lineName->value;
                $data['lineNumber'] = $position->lineNumber->value;
                break;
            case $position instanceof DenshinPosition:
                $data['lineName'] = $position->lineName->value;
                $data['lineNumber'] = $position->lineNumber->value;
                break;
            case $position instanceof BuildingPosition:
                $data['buildingName'] = $position->buildingName->value;
                break;
            case $position instanceof OtherPosition:
                break;
        }

        $data['imageURLs'] = [];
        foreach ($images as $image) {
            $data['imageURLs'][] = $image->value;
        }

        return $data;
    }
}

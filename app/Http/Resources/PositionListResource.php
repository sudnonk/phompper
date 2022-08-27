<?php

namespace App\Http\Resources;

use App\Domain\Entity\Position\Position;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class PositionListResource extends JsonResource
{
    #[ArrayShape([
        [
            'geoHash' => "string",
            'latitude' => "string",
            'longitude' => "string",
            'type' => "string",
        ],
    ])] public function toArray($request): array
    {
        /** @var Position[] $positions */
        $positions = $this->resource;

        $data = [];
        foreach ($positions as $position) {
            $datum = [
                'geoHash' => $position->geoHash->value,
                'latitude' => $position->geoHash->latitude->value,
                'longitude' => $position->geoHash->longitude->value,
                'type' => $position->getPositionType()->value,
            ];
            $data[] = $datum;
        }

        return $data;
    }
}

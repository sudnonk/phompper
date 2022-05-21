<?php

namespace App\Http\Resources;

use App\Domain\Entity\Position\Position;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionListResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Position[] $positions */
        $positions = $this->resource;

        $data = [];
        foreach ($positions as $position) {
            $datum = [
                'geoHash'=>$position->geoHash->value,
                'latitude' => $position->geoHash->latitude->value,
                'longitude' => $position->geoHash->longitude->value,
                'type' => $position->type->value,
            ];

            $data[] = $datum;
        }

        return $data;
    }
}

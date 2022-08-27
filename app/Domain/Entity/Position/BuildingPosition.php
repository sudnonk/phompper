<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\Position\BuildingName;
use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\PositionDetailId;
use App\Domain\ValueObject\Position\PositionNote;
use App\Domain\ValueObject\Position\PositionType;

class BuildingPosition extends PositionDetail
{
    public function __construct(
        PositionDetailId $id,
        GeoHash $geoHash,
        public readonly BuildingName $buildingName,
        PositionNote $positionNote
    ) {
        parent::__construct($id, $geoHash, $positionNote, PositionType::BUILDING);
    }
}

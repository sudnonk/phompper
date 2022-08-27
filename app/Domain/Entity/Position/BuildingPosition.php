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
        public readonly PositionDetailId $id,
        public readonly GeoHash $geoHash,
        public readonly BuildingName $buildingName,
        public readonly PositionNote $positionNote
    ) {
        parent::__construct($id, $geoHash, $positionNote, PositionType::BUILDING);
    }
}

<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\BuildingName;
use App\Domain\ValueObject\GeoHash;
use App\Domain\ValueObject\PositionNote;
use App\Domain\ValueObject\PositionType;

class BuildingPosition extends Position
{
    public function __construct(
        public readonly GeoHash $geoHash,
        public readonly BuildingName $buildingName,
        public readonly PositionNote $positionNote
    ) {
        parent::__construct($geoHash, $positionNote,PositionType::BUILDING);
    }
}

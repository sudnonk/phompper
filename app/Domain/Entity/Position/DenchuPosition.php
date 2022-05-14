<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\GeoHash;
use App\Domain\ValueObject\LineName;
use App\Domain\ValueObject\LineNumber;
use App\Domain\ValueObject\PositionNote;
use App\Domain\ValueObject\PositionType;

class DenchuPosition extends Position
{
    public function __construct(
        public readonly GeoHash $geoHash,
        public readonly LineName $lineName,
        public readonly LineNumber $lineNumber,
        public readonly PositionNote $positionNote
    ) {
        parent::__construct($geoHash, $positionNote,PositionType::DENCHU);
    }

}

<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\GeoHash;
use App\Domain\ValueObject\LineName;
use App\Domain\ValueObject\LineNumber;
use App\Domain\ValueObject\PositionNote;
use App\Domain\ValueObject\PositionType;

class DenshinPosition extends Position
{
    public function __construct(
        public readonly GeoHash $geoHash,
        public readonly LineName $lineName,
        public readonly LineNumber $lineNumber,
        public readonly PositionNote $positionNote
    ) {
        parent::__construct($geoHash, $positionNote, PositionType::DENSHIN);
    }
}

<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\LineName;
use App\Domain\ValueObject\Position\LineNumber;
use App\Domain\ValueObject\Position\PositionDetailId;
use App\Domain\ValueObject\Position\PositionNote;
use App\Domain\ValueObject\Position\PositionType;

class DenchuPosition extends PositionDetail
{
    public function __construct(
        PositionDetailId $id,
        GeoHash $geoHash,
        public readonly LineName $lineName,
        public readonly LineNumber $lineNumber,
        PositionNote $positionNote
    ) {
        parent::__construct($id, $geoHash, $positionNote, PositionType::DENCHU);
    }

}

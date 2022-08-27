<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\LineName;
use App\Domain\ValueObject\Position\LineNumber;
use App\Domain\ValueObject\Position\PositionDetailId;
use App\Domain\ValueObject\Position\PositionNote;
use App\Domain\ValueObject\Position\PositionType;

class DenshinPosition extends PositionDetail
{
    public function __construct(
        public readonly PositionDetailId $id,
        public readonly GeoHash $geoHash,
        public readonly LineName $lineName,
        public readonly LineNumber $lineNumber,
        public readonly PositionNote $positionNote
    ) {
        parent::__construct($id, $geoHash, $positionNote, PositionType::DENSHIN);
    }
}

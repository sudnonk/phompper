<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\PositionDetailId;
use App\Domain\ValueObject\Position\PositionNote;
use App\Domain\ValueObject\Position\PositionType;

class OtherPosition extends PositionDetail
{
    public function __construct(
        public readonly PositionDetailId $id,
        public readonly GeoHash $geoHash,
        public readonly PositionNote $positionNote
    ) {
        if ($this->positionNote->getLength() < 1) {
            throw new \InvalidArgumentException("地点種別「その他」の場合は、備考欄に入力してください。");
        }
        parent::__construct($id, $geoHash, $positionNote, PositionType::OTHER);
    }
}

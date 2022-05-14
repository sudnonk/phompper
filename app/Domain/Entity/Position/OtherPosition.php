<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\GeoHash;
use App\Domain\ValueObject\PositionNote;
use App\Domain\ValueObject\PositionType;

class OtherPosition extends Position
{
    public function __construct(public readonly GeoHash $geoHash, public readonly PositionNote $positionNote)
    {
        if ($this->positionNote->getLength() < 1) {
            throw new \InvalidArgumentException("地点種別「その他」の場合は、備考欄に入力してください。");
        }
        parent::__construct($geoHash, $positionNote, PositionType::OTHER);
    }
}

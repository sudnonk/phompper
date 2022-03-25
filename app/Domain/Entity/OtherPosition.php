<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\PositionType;

class OtherPosition extends Position
{
    protected function setType(): PositionType
    {
        return new PositionType(PositionType::OTHER);
    }
}

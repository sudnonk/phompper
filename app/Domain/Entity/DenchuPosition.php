<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\PositionType;

class DenchuPosition extends DenshinPosition
{
    protected function setType(): PositionType
    {
        return new PositionType(PositionType::DENCHU);
    }

}

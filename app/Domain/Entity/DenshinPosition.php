<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\GeoHash;
use App\Domain\ValueObject\LineName;
use App\Domain\ValueObject\LineNumber;
use App\Domain\ValueObject\PositionNote;
use App\Domain\ValueObject\PositionType;

class DenshinPosition extends Position
{
    /** @var LineName $name */
    protected $name;
    /** @var LineNumber $number */
    protected $number;

    public function __construct(
        GeoHash $geoHash,
        LineName $lineName,
        LineNumber $lineNumber,
        PositionNote $positionNote
    ) {
        parent::__construct($geoHash, $positionNote);
        $this->name = $lineName;
        $this->number = $lineNumber;
    }

    protected function setType(): PositionType
    {
        return new PositionType(PositionType::DENSHIN);
    }


    /**
     * @return LineName
     */
    public function getName(): LineName
    {
        return $this->name;
    }

    /**
     * @return LineNumber
     */
    public function getNumber(): LineNumber
    {
        return $this->number;
    }
}

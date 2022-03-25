<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\BuildingName;
use App\Domain\ValueObject\GeoHash;
use App\Domain\ValueObject\PositionNote;
use App\Domain\ValueObject\PositionType;

class BuildingPosition extends Position
{
    /** @var BuildingName $name */
    protected $name;

    public function __construct(
        GeoHash $geoHash,
        BuildingName $buildingName,
        PositionNote $positionNote
    ) {
        parent::__construct($geoHash, $positionNote);
        $this->name = $buildingName;
    }

    protected function setType(): PositionType
    {
        return new PositionType(PositionType::BUILDING);
    }


    /**
     * @return BuildingName
     */
    public function getName(): BuildingName
    {
        return $this->name;
    }


}

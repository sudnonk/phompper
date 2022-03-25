<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\GeoHash;
use App\Domain\ValueObject\PositionNote;
use App\Domain\ValueObject\PositionType;

abstract class Position
{
    /** @var GeoHash $geoHash この地点のGeoHash */
    protected $geoHash;
    /** @var PositionType $type この地点の種別 */
    protected $type;
    /** @var PositionNote $note この地点の備考 */
    protected $note;

    protected function __construct(
        GeoHash $geoHash,
        PositionNote $positionNote
    ) {
        $this->geoHash = $geoHash;
        $this->type = $this->setType();
        $this->note = $positionNote;
    }

    abstract protected function setType():PositionType;

    /**
     * @return GeoHash
     */
    public function getGeoHash(): GeoHash
    {
        return $this->geoHash;
    }

    /**
     * @return PositionType
     */
    public function getType(): PositionType
    {
        return $this->type;
    }

    /**
     * @return PositionNote
     */
    public function getNote(): PositionNote
    {
        return $this->note;
    }
}

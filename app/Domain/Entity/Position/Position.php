<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\Latitude;
use App\Domain\ValueObject\Position\Longitude;

final class Position
{
    public readonly GeoHash $geoHash;
    /** @var PositionDetail[] $positionDetails */
    protected array $positionDetails;

    /**
     * @param GeoHash               $geoHash
     * @param array<PositionDetail> $positionDetails
     */
    public function __construct(
        GeoHash $geoHash,
        array $positionDetails = []
    ) {
        $this->geoHash = $geoHash;
        $this->positionDetails = $positionDetails;
    }

    public static function fromString(string $geoHash, array $positionDetails = []): self
    {
        return new self(new GeoHash($geoHash), $positionDetails);
    }

    /**
     * @param Latitude         $latitude
     * @param Longitude        $longitude
     * @param PositionDetail[] $positionDetails
     * @return static
     */
    public static function fromLatLng(Latitude $latitude, Longitude $longitude, array $positionDetails = []): self
    {
        $geoHash = GeoHash::fromLatLng($latitude, $longitude);
        return new self($geoHash, $positionDetails);
    }

    public function addPositionDetail(PositionDetail $positionDetail): self
    {
        $this->positionDetails[] = $positionDetail;
        return $this;
    }

    public function addPositionDetails(array $positionDetails): self
    {
        $this->positionDetails = array_merge($this->positionDetails, $positionDetails);
        return $this;
    }

    /**
     * @return PositionDetail[]
     */
    public function getPositionDetails(): array
    {
        return $this->positionDetails;
    }
}

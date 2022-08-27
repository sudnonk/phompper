<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\PositionType;

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

    public function addPositionDetail(PositionDetail $positionDetail): self
    {
        $this->positionDetails[] = $positionDetail;
        return $this;
    }

    /**
     * @return PositionDetail[]
     */
    public function getPositionDetails(): array
    {
        return $this->positionDetails;
    }

    public function getPositionType(): PositionType
    {
        /*
         * positionDetails配列で一番上にあるPositionDetailsのTypeが使用されているが、
         * 複数存在する場合は実質的に、一つの電柱に複数のLineNameがある場合なので、特に問題なさそう
         */
        if (isset($this->positionDetails[0])) {
            return $this->positionDetails[0]->positionType;
        } else {
            return PositionType::OTHER;
        }
    }
}

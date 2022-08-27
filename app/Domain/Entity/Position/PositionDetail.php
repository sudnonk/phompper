<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Position\BuildingName;
use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\LineName;
use App\Domain\ValueObject\Position\LineNumber;
use App\Domain\ValueObject\Position\PositionDetailId;
use App\Domain\ValueObject\Position\PositionNote;
use App\Domain\ValueObject\Position\PositionType;
use App\Domain\ValueObject\Uuid;

abstract class PositionDetail
{
    public readonly DateTimeImmutable $createdAt;

    protected function __construct(
        public readonly PositionDetailId $positionId,
        public readonly GeoHash $geoHash,
        public readonly PositionNote $positionNote,
        public readonly PositionType $positionType,
        DateTimeImmutable $createdAt = null
    ) {
        $this->createdAt = $createdAt ?? DateTimeImmutable::now();
    }

    /**
     * 新規登録時/DBからの取得時用：文字列からPositionDetailを作る
     *
     * @param Uuid|string|null $positionId
     * @param GeoHash|string   $geoHash
     * @param string           $positionType
     * @param string|null      $lineName
     * @param string|null      $lineNumber
     * @param string|null      $buildingName
     * @param string|null      $positionNote
     * @return PositionDetail
     */
    public static function createPositionDetailFromString(
        Uuid|string|null $positionId,
        GeoHash|string $geoHash,
        string $positionType,
        ?string $lineName,
        ?string $lineNumber,
        ?string $buildingName,
        ?string $positionNote
    ): PositionDetail {
        if (is_string($positionId)) {
            $positionId = PositionDetailId::fromString($positionId);
        } elseif ($positionId === null) {
            $positionId = PositionDetailId::generate();
        }
        if (is_string($geoHash)) {
            $geoHash = new GeoHash($geoHash);
        }

        $positionNote = new PositionNote($positionNote);
        $positionType = PositionType::tryFromString($positionType);
        return match ($positionType) {
            PositionType::DENCHU => new DenchuPosition(
                $positionId,
                $geoHash,
                new LineName($lineName),
                new LineNumber($lineNumber),
                $positionNote
            ),
            PositionType::DENSHIN => new DenshinPosition(
                $positionId,
                $geoHash,
                new LineName($lineName),
                new LineNumber($lineNumber),
                $positionNote
            ),
            PositionType::BUILDING => new BuildingPosition(
                $positionId,
                $geoHash,
                new BuildingName($buildingName),
                $positionNote
            ),
            PositionType::OTHER => new OtherPosition(
                $positionId,
                $geoHash,
                $positionNote
            ),
        };
    }
}

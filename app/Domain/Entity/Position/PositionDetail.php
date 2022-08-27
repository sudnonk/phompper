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
    public readonly PositionDetailId $id;
    public readonly GeoHash $geoHash;
    public readonly PositionType $type;
    public readonly DateTimeImmutable $createdAt;

    protected function __construct(
        PositionDetailId $positionId,
        GeoHash $geoHash,
        PositionNote $positionNote,
        PositionType $positionType,
        DateTimeImmutable $createdAt = null
    ) {
        $this->id = $positionId;
        $this->type = $positionType;
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
            PositionType::DENCHU => self::denchuFromString(id: $positionId, geoHash: $geoHash, line: $lineName,
                number: $lineNumber, note: $positionNote),
            PositionType::DENSHIN => self::denshinFromString(id: $positionId, geoHash: $geoHash, line: $lineName,
                number: $lineNumber, note: $positionNote),
            PositionType::BUILDING => self::buildingFromString(id: $positionId, geoHash: $geoHash, name: $buildingName,
                note: $positionNote),
            PositionType::OTHER => self::otherFromString(id: $positionId, geoHash: $geoHash, note: $positionNote),
        };
    }

    protected static function denchuFromString(
        PositionDetailId $id,
        GeoHash $geoHash,
        string $line,
        string $number,
        PositionNote $note
    ): DenchuPosition {
        return new DenchuPosition(
            $id,
            $geoHash,
            new LineName($line),
            new LineNumber($number),
            $note
        );
    }

    protected static function denshinFromString(
        PositionDetailId $id,
        GeoHash $geoHash,
        string $line,
        string $number,
        PositionNote $note
    ): DenshinPosition {
        return new DenshinPosition(
            $id,
            $geoHash,
            new LineName($line),
            new LineNumber($number),
            $note
        );
    }

    protected static function buildingFromString(
        PositionDetailId $id,
        GeoHash $geoHash,
        string $name,
        PositionNote $note
    ): BuildingPosition {
        return new BuildingPosition(
            $id,
            $geoHash,
            new BuildingName($name),
            $note
        );
    }

    protected static function otherFromString(
        PositionDetailId $id,
        GeoHash $geoHash,
        PositionNote $note
    ): OtherPosition {
        return new OtherPosition(
            $id,
            $geoHash,
            $note
        );
    }

}

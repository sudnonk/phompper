<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Position\BuildingName;
use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\Latitude;
use App\Domain\ValueObject\Position\LineName;
use App\Domain\ValueObject\Position\LineNumber;
use App\Domain\ValueObject\Position\Longitude;
use App\Domain\ValueObject\Position\PositionId;
use App\Domain\ValueObject\Position\PositionNote;
use App\Domain\ValueObject\Position\PositionType;
use App\Exceptions\ValidatorInvalidArgumentException;

abstract class Position
{
    public readonly PositionId $id;
    /** @var PositionType $type この地点の種別 */
    public readonly PositionType $type;
    public readonly DateTimeImmutable $createdAt;

    protected function __construct(
        PositionId $positionId,
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
     * 新規登録時に文字列からPositionを作る
     *
     * @param string      $latitude
     * @param string      $longitude
     * @param string      $positionType
     * @param string|null $lineName
     * @param string|null $lineNumber
     * @param string|null $buildingName
     * @param string|null $positionNote
     * @return Position
     * @throws ValidatorInvalidArgumentException
     */
    public static function createPosition(
        string $latitude,
        string $longitude,
        string $positionType,
        ?string $lineName,
        ?string $lineNumber,
        ?string $buildingName,
        ?string $positionNote
    ): Position {
        $positionId = PositionId::generate();
        $latitude = new Latitude($latitude);
        $longitude = new Longitude($longitude);
        $geohash = GeoHash::fromLatLng($latitude, $longitude);

        $positionNote = new PositionNote($positionNote);
        $positionType = PositionType::tryFromString($positionType);
        return match ($positionType) {
            PositionType::DENCHU => self::denchuFromString(id: $positionId, geoHash: $geohash, line: $lineName,
                number: $lineNumber, note: $positionNote),
            PositionType::DENSHIN => self::denshinFromString(id: $positionId, geoHash: $geohash, line: $lineName,
                number: $lineNumber, note: $positionNote),
            PositionType::BUILDING => self::buildingFromString(id: $positionId, geoHash: $geohash, name: $buildingName,
                note: $positionNote),
            PositionType::OTHER => self::otherFromString(id: $positionId, geoHash: $geohash, note: $positionNote),
        };
    }

    protected static function denchuFromString(
        PositionId $id,
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
        PositionId $id,
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
        PositionId $id,
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
        PositionId $id,
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

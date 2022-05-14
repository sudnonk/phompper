<?php

namespace App\Domain\Entity\Position;

use App\Domain\ValueObject\Position\BuildingName;
use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\Latitude;
use App\Domain\ValueObject\Position\LineName;
use App\Domain\ValueObject\Position\LineNumber;
use App\Domain\ValueObject\Position\Longitude;
use App\Domain\ValueObject\Position\PositionNote;
use App\Domain\ValueObject\Position\PositionType;
use App\Exceptions\ValidatorInvalidArgumentException;

abstract class Position
{
    /** @var PositionType $type この地点の種別 */
    public readonly PositionType $type;

    protected function __construct(
        public readonly GeoHash $geoHash,
        public readonly PositionNote $positionNote,
        public readonly PositionType $positionType
    ) {
        $this->type = $this->positionType;
    }

    /**
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
    public static function fromStrings(
        string $latitude,
        string $longitude,
        string $positionType,
        ?string $lineName,
        ?string $lineNumber,
        ?string $buildingName,
        ?string $positionNote
    ): Position {
        $latitude = new Latitude($latitude);
        $longitude = new Longitude($longitude);
        $geohash = GeoHash::fromLatLng($latitude, $longitude);

        $positionNote = new PositionNote($positionNote);
        $positionType = PositionType::tryFromString($positionType);
        return match ($positionType) {
            PositionType::DENCHU => self::denchuFromString(geohash: $geohash, line: $lineName,
                number: $lineNumber, note: $positionNote),
            PositionType::DENSHIN => self::denshinFromString(geohash: $geohash, line: $lineName,
                number: $lineNumber, note: $positionNote),
            PositionType::BUILDING => self::buildingFromString(geoHash: $geohash, name: $buildingName,
                note: $positionNote),
            PositionType::OTHER => self::otherFromString(geoHash: $geohash, note: $positionNote),
        };
    }

    protected static function denchuFromString(
        GeoHash $geoHash,
        string $line,
        string $number,
        PositionNote $note
    ): DenchuPosition {
        return new DenchuPosition(
            $geoHash,
            new LineName($line),
            new LineNumber($number),
            $note
        );
    }

    protected static function denshinFromString(
        GeoHash $geoHash,
        string $line,
        string $number,
        PositionNote $note
    ): DenshinPosition {
        return new DenshinPosition(
            $geoHash,
            new LineName($line),
            new LineNumber($number),
            $note
        );
    }

    protected static function buildingFromString(
        GeoHash $geoHash,
        string $name,
        PositionNote $note
    ): BuildingPosition {
        return new BuildingPosition(
            $geoHash,
            new BuildingName($name),
            $note
        );
    }

    protected static function otherFromString(
        GeoHash $geoHash,
        PositionNote $note
    ): OtherPosition {
        return new OtherPosition(
            $geoHash,
            $note
        );
    }
}

<?php

namespace App\Infrastructure\Model;

use App\Domain\Entity\Position\BuildingPosition;
use App\Domain\Entity\Position\DenchuPosition;
use App\Domain\Entity\Position\DenshinPosition;
use App\Domain\Entity\Position\OtherPosition;
use App\Domain\Entity\Position\Position;
use App\Domain\ValueObject\Position\BuildingName;
use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\LineName;
use App\Domain\ValueObject\Position\LineNumber;
use App\Domain\ValueObject\Position\PositionNote;
use App\Domain\ValueObject\Position\PositionType;
use App\Infrastructure\Database\PositionRepository;
use Illuminate\Support\Facades\DB;

class PositionModel
{
    public static function saveDenchuPosition(DenchuPosition $position): Position
    {
        DB::table(PositionRepository::TABLE_NAME)->insert([
            'geohash' => $position->geoHash->value,
            'type' => $position->type->value,
            'line' => $position->lineName->value,
            'number' => $position->lineNumber->value,
            'note' => $position->positionNote->value,
            'created_at' => $position->createdAt->getAsFormat(),
        ]);
        return $position;
    }

    public static function saveDenshinPosition(DenshinPosition $position): Position
    {
        DB::table(PositionRepository::TABLE_NAME)->insert([
            'geohash' => $position->geoHash->value,
            'type' => $position->type->value,
            'line' => $position->lineName->value,
            'number' => $position->lineNumber->value,
            'note' => $position->positionNote->value,
            'created_at' => $position->createdAt->getAsFormat(),
        ]);
        return $position;
    }

    public static function saveBuildingPosition(BuildingPosition $position): Position
    {
        DB::table(PositionRepository::TABLE_NAME)->insert([
            'geohash' => $position->geoHash->value,
            'type' => $position->type->value,
            'name' => $position->buildingName->value,
            'note' => $position->positionNote->value,
            'created_at' => $position->createdAt->getAsFormat(),
        ]);
        return $position;
    }

    public static function saveOtherPosition(OtherPosition $position): Position
    {
        DB::table(PositionRepository::TABLE_NAME)->insert([
            'geohash' => $position->geoHash->value,
            'type' => $position->type->value,
            'note' => $position->positionNote->value,
            'created_at' => $position->createdAt->getAsFormat(),
        ]);
        return $position;
    }

    public static function makeFromDB(
        string $geoHash,
        string $positionType,
        ?string $lineName,
        ?string $lineNumber,
        ?string $buildingName,
        ?string $positionNote
    ): Position {
        $type = PositionType::tryFrom($positionType);
        if ($type === null) {
            throw new \InvalidArgumentException("地点種別が不明です。");
        }

        return match ($type) {
            PositionType::DENCHU => new DenchuPosition(
                geoHash: new GeoHash($geoHash),
                lineName: new LineName($lineName), lineNumber: new LineNumber($lineNumber),
                positionNote: new PositionNote($positionNote)
            ),
            PositionType::DENSHIN => new DenshinPosition(
                geoHash: new GeoHash($geoHash),
                lineName: new LineName($lineName), lineNumber: new LineNumber($lineNumber),
                positionNote: new PositionNote($positionNote)
            ),
            PositionType::BUILDING => new BuildingPosition(
                geoHash: new GeoHash($geoHash),
                buildingName: new BuildingName($buildingName),
                positionNote: new PositionNote($positionNote),
            ),
            PositionType::OTHER => new OtherPosition(
                geoHash: new GeoHash($geoHash),
                positionNote: new PositionNote($positionNote),
            )
        };
    }

}

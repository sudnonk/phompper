<?php

namespace App\Infrastructure\Database;

use App\Domain\Entity\Position\BuildingPosition;
use App\Domain\Entity\Position\DenchuPosition;
use App\Domain\Entity\Position\DenshinPosition;
use App\Domain\Entity\Position\OtherPosition;
use App\Domain\Entity\Position\Position;
use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\PositionType;
use App\Infrastructure\Model\PositionModel;
use Illuminate\Support\Facades\DB;

class PositionRepository implements PositionRepositoryInterface
{
    public const TABLE_NAME = "positions";

    /**
     * @param Position $position
     * @return Position
     */
    public function savePosition(Position $position): Position
    {
        switch ($position->type) {
            case PositionType::DENSHIN:
                /** @var DenshinPosition $position */
                return PositionModel::saveDenshinPosition($position);
            case PositionType::DENCHU:
                /** @var DenchuPosition $position */
                return PositionModel::saveDenchuPosition($position);
            case PositionType::BUILDING:
                /** @var BuildingPosition $position */
                return PositionModel::saveBuildingPosition($position);
            case PositionType::OTHER:
                /** @var OtherPosition $position */
                return PositionModel::saveOtherPosition($position);
            default:
                throw new \ValueError("地点種別が不明です。");
        }
    }

    /**
     * @param GeoHash $geoHash
     * @return Position
     */
    public function find(GeoHash $geoHash): Position
    {
        $data = DB::table(self::TABLE_NAME)
                  ->select(['geoHash', 'type', 'lineName', 'lineNumber', 'buildingName', 'note'])
                  ->where('geoHash', '=', $geoHash->value)
                  ->first();
        return PositionModel::makeFromDB(
            geoHash: $data['geoHash'],
            positionType: $data['type'], lineName: $data['lineName'], lineNumber: $data['lineNumber'],
            buildingName: $data['buildingName'], positionNote: $data['note']
        );
    }

    public function findAll(): array
    {
        $positions = [];
        $data = DB::table(self::TABLE_NAME)
                  ->select(['geoHash', 'type', 'lineName', 'lineNumber', 'buildingName', 'note'])
                  ->get();
        foreach ($data as $datum) {
            $positions[] = PositionModel::makeFromDB(
                geoHash: $datum['geoHash'],
                positionType: $datum['type'], lineName: $datum['lineName'], lineNumber: $datum['lineNumber'],
                buildingName: $datum['buildingName'], positionNote: $datum['note']
            );
        }

        return $positions;
    }

    public function delete(GeoHash $geoHash): void
    {
        DB::table(self::TABLE_NAME)->where('geoHash', '=', $geoHash->value)->delete();
    }

}

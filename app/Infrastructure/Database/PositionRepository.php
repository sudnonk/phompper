<?php

namespace App\Infrastructure\Database;

use App\Domain\Entity\Position\BuildingPosition;
use App\Domain\Entity\Position\DenchuPosition;
use App\Domain\Entity\Position\DenshinPosition;
use App\Domain\Entity\Position\OtherPosition;
use App\Domain\Entity\Position\Position;
use App\Domain\Entity\Position\PositionDetail;
use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\PositionType;
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
        foreach ($position->getPositionDetails() as $positionDetail) {
            switch ($positionDetail->type) {
                case PositionType::DENSHIN:
                    /** @var DenshinPosition $positionDetail */
                    $this->saveDenshinPosition($positionDetail);
                    break;
                case PositionType::DENCHU:
                    /** @var DenchuPosition $positionDetail */
                    $this->saveDenchuPosition($positionDetail);
                    break;
                case PositionType::BUILDING:
                    /** @var BuildingPosition $positionDetail */
                    $this->saveBuildingPosition($positionDetail);
                    break;
                case PositionType::OTHER:
                    /** @var OtherPosition $positionDetail */
                    $this->saveOtherPosition($positionDetail);
                    break;
            }
        }
        return $position;
    }

    public function saveDenchuPosition(DenchuPosition $position): PositionDetail
    {
        DB::table(PositionRepository::TABLE_NAME)->insert([
            'id' => $position->id->value,
            'geohash' => $position->geoHash->value,
            'type' => $position->type->value,
            'line' => $position->lineName->value,
            'number' => $position->lineNumber->value,
            'note' => $position->positionNote->value,
            'created_at' => $position->createdAt->getAsFormat(),
        ]);
        return $position;
    }

    public function saveDenshinPosition(DenshinPosition $position): PositionDetail
    {
        DB::table(PositionRepository::TABLE_NAME)->insert([
            'id' => $position->id->value,
            'geohash' => $position->geoHash->value,
            'type' => $position->type->value,
            'line' => $position->lineName->value,
            'number' => $position->lineNumber->value,
            'note' => $position->positionNote->value,
            'created_at' => $position->createdAt->getAsFormat(),
        ]);
        return $position;
    }

    public function saveBuildingPosition(BuildingPosition $position): PositionDetail
    {
        DB::table(PositionRepository::TABLE_NAME)->insert([
            'id' => $position->id->value,
            'geohash' => $position->geoHash->value,
            'type' => $position->type->value,
            'name' => $position->buildingName->value,
            'note' => $position->positionNote->value,
            'created_at' => $position->createdAt->getAsFormat(),
        ]);
        return $position;
    }

    public function saveOtherPosition(OtherPosition $position): PositionDetail
    {
        DB::table(PositionRepository::TABLE_NAME)->insert([
            'id' => $position->id->value,
            'geohash' => $position->geoHash->value,
            'type' => $position->type->value,
            'note' => $position->positionNote->value,
            'created_at' => $position->createdAt->getAsFormat(),
        ]);
        return $position;
    }

    /**
     * @param GeoHash $geoHash
     * @return Position
     */
    public function find(GeoHash $geoHash): Position
    {
        $position = new Position($geoHash);

        $position_details = DB::table(self::TABLE_NAME)
                              ->select(['id', 'type', 'line', 'number', 'name', 'note'])
                              ->where('geohash', '=', $position->geoHash->value)
                              ->get();

        /** @var \stdClass $position_detail */
        foreach ($position_details as $position_detail) {
            $position->addPositionDetail(
                PositionDetail::createPositionDetailFromString(
                    positionId: $position_detail->id,
                    geoHash: $position->geoHash,
                    positionType: $position_detail->type,
                    lineName: $position_detail->line,
                    lineNumber: $position_detail->number,
                    buildingName: $position_detail->name,
                    positionNote: $position_detail->note
                )
            );
        }

        return $position;
    }

    public function findAll(): array
    {
        /** @var array<string,Position> $positions */
        $positions = [];
        $position_details = DB::table(self::TABLE_NAME)
                              ->select(['id', 'geohash', 'type', 'line', 'number', 'name', 'note'])
                              ->get();
        /** @var \stdClass $position_detail */
        foreach ($position_details as $position_detail) {
            $detail = PositionDetail::createPositionDetailFromString(
                positionId: $position_detail->id,
                geoHash: $position_detail->geohash,
                positionType: $position_detail->type,
                lineName: $position_detail->line,
                lineNumber: $position_detail->number,
                buildingName: $position_detail->name,
                positionNote: $position_detail->note
            );

            if (isset($positions[$detail->geoHash->value])) {
                $positions[$detail->geoHash->value]->addPositionDetail($detail);
            } else {
                $positions[$detail->geoHash->value] = new Position($detail->geoHash, [$detail]);
            }
        }

        return $positions;
    }

    public function delete(GeoHash $geoHash): void
    {
        DB::table(self::TABLE_NAME)->where('geoHash', '=', $geoHash->value)->delete();
    }
}

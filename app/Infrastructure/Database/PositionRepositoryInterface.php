<?php

namespace App\Infrastructure\Database;

use App\Domain\Entity\Position\Position;
use App\Domain\ValueObject\Position\GeoHash;

interface PositionRepositoryInterface
{
    public function savePosition(Position $position): Position;

    public function find(GeoHash $geoHash): Position;

    public function findAll(): array;

    public function delete(GeoHash $geoHash): void;
}

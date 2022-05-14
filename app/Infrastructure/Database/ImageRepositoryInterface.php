<?php

namespace App\Infrastructure\Database;

use App\Domain\Entity\Image\ImagePath;
use App\Domain\ValueObject\Position\GeoHash;

interface ImageRepositoryInterface
{
    public function saveImage(string $tmpPath, ImagePath $imagePath): ImagePath;

    public function deleteImage(ImagePath $imagePath): ImagePath;

    public function findImages(GeoHash $geoHash): array;
}

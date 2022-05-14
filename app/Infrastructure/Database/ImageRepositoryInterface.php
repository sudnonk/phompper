<?php

namespace App\Infrastructure\Database;

use App\Domain\Entity\Image\ImagePath;
use App\Domain\ValueObject\Image\ImageURL;
use App\Domain\ValueObject\Position\GeoHash;

interface ImageRepositoryInterface
{
    /**
     * @param string    $tmpPath
     * @param ImagePath $imagePath
     * @return ImagePath
     */
    public function saveImage(string $tmpPath, ImagePath $imagePath): ImagePath;

    /**
     * @param ImagePath $imagePath
     * @return ImagePath
     */
    public function deleteImage(ImagePath $imagePath): ImagePath;

    /**
     * @param GeoHash $geoHash
     * @return ImagePath[]
     */
    public function findImages(GeoHash $geoHash): array;

    /**
     * @param GeoHash $geoHash
     * @return ImageURL[]
     */
    public function findImageURLs(GeoHash $geoHash): array;
}

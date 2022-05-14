<?php

namespace App\Infrastructure\Storage;

use App\Domain\Entity\Image\ImagePath;

interface ImageStorageInterface
{
    public function saveImage(string $tmpPath, ImagePath $imagePath): ImagePath;

    public function deleteImage(ImagePath $imagePath): void;

    public function getImageURL(ImagePath $imagePath): string;
}

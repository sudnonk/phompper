<?php

namespace App\Infrastructure\Model;

use App\Domain\Entity\Image\ImagePath;

class ImageModel
{
    public static function makeFromDB(
        string $fileName
    ): ImagePath {
        return ImagePath::parse($fileName);
    }
}

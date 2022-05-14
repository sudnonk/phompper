<?php

namespace App\Domain\ValueObject\Image;

enum Extension: string
{
    case JPG = "jpg";
    case PNG = "png";
    case UNEXPECTED = "unknown";

    public static function determineFromFile(string $path): self
    {
        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf('%sは読み込めません。', $path));
        }
        $finfo = new \finfo(FILEINFO_MIME);
        return match ($finfo->file($path)) {
            'image/jpeg' => self::JPG,
            'image/png' => self::PNG,
            default => self::UNEXPECTED
        };
    }

    public static function fromString(string $extLike): self
    {
        return match ($extLike) {
            'jpg', 'jpeg' => self::JPG,
            'png' => self::PNG,
            default => self::UNEXPECTED
        };
    }

}

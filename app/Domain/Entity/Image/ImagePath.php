<?php

namespace App\Domain\Entity\Image;

use App\Domain\Entity\Position\Position;
use App\Domain\ValueObject\Image\Extension;
use App\Domain\ValueObject\Image\FileHash;
use App\Domain\ValueObject\Position\GeoHash;
use App\Exceptions\ValidatorInvalidArgumentException;
use Illuminate\Http\UploadedFile;

class ImagePath
{
    public function __construct(
        public readonly GeoHash $geohash,
        public readonly FileHash $filehash,
        public readonly Extension $extension
    ) {
    }

    public function concat(): string
    {
        return sprintf('%s_%s.%s', $this->geohash->value, $this->filehash->value, $this->extension->value);
    }

    public function getURL(): string
    {
        return env('GOOGLE_BUCKET_URL') . $this->concat();
    }

    /**
     * @param string $filename
     * @return static
     * @throws ValidatorInvalidArgumentException|\InvalidArgumentException
     */
    public static function parse(string $filename): self
    {
        //ファイル名はGeoHash_FileHash.extensionの形式のはず
        $format = sprintf("/^([0-9a-f]{%d})_([0-9a-f]{%d})\.(.+)$/", GeoHash::PRECISION, FileHash::LENGTH);
        $matches = [];
        preg_match($format, $filename, $matches);
        return new self(
            new GeoHash($matches[1]),
            new FileHash($matches[2]),
            Extension::fromString($matches[3])
        );
    }

    /**
     * @param Position     $position
     * @param UploadedFile $uploadedFile
     * @return static
     * @throws \InvalidArgumentException
     */
    public static function createFromUploadedFile(Position $position, UploadedFile $uploadedFile): self
    {
        $tmpPath = $uploadedFile->getRealPath();
        if ($tmpPath === false) {
            throw new \InvalidArgumentException("ファイルが保存されていません。");
        }
        $ext = Extension::determineFromFile($tmpPath);
        if ($ext === Extension::UNEXPECTED) {
            throw new \InvalidArgumentException("ファイル形式がJPGでもPNGでも有りません。");
        }
        return new self(
            $position->geoHash,
            FileHash::fromFile($tmpPath),
            $ext
        );
    }
}

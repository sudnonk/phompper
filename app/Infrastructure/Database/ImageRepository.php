<?php

namespace App\Infrastructure\Database;

use App\Domain\Entity\Image\ImagePath;
use App\Domain\ValueObject\Position\GeoHash;
use App\Infrastructure\Model\ImageModel;
use App\Infrastructure\Storage\ImageStorageInterface;
use Illuminate\Support\Facades\DB;

class ImageRepository implements ImageRepositoryInterface
{
    protected ImageStorageInterface $imageStorage;
    protected const TABLE_NAME = "position_images";

    public function saveImage(string $tmpPath, ImagePath $imagePath): ImagePath
    {
        $this->imageStorage->saveImage($tmpPath, $imagePath);
        DB::table(self::TABLE_NAME)->insert([
            'fileName' => $imagePath->concat(),
            'geoHash' => $imagePath->geohash->value,
        ]);
        return $imagePath;
    }

    public function deleteImage(ImagePath $imagePath): ImagePath
    {
        $this->imageStorage->deleteImage($imagePath);
        DB::table(self::TABLE_NAME)->where('fileName', '=', $imagePath->concat())->delete();
        return $imagePath;
    }

    public function findImages(GeoHash $geoHash): array
    {
        $imagePaths = [];
        $data = DB::table(self::TABLE_NAME)
                  ->where('geoHash', '=', $geoHash)
                  ->select(['fileName'])
                  ->get();
        foreach ($data as $datum) {
            $imagePaths[] = ImageModel::makeFromDB($datum['fileName']);
        }

        return $imagePaths;
    }

    public function findImageURLs(GeoHash $geoHash): array
    {
        $imageURLs = [];
        $imagePaths = $this->findImages($geoHash);
        foreach ($imagePaths as $imagePath) {
            $imageURLs[] = $this->imageStorage->getImageURL($imagePath);
        }
        return $imageURLs;
    }

}

<?php

namespace App\Infrastructure\Storage;

use App\Domain\Entity\Image\ImagePath;
use Google\Cloud\Storage\StorageClient;

class GoogleCloudStorage implements ImageStorageInterface
{
    public function saveImage(string $tmpPath, ImagePath $imagePath): ImagePath
    {
        $storage = new StorageClient([
            'projectID' => "",
            'keyFile' => "",
        ]);
        $bucket = $storage->bucket("");
        $file = fopen($tmpPath, 'r');
        $object = $bucket->upload($file, [
            'name' => $imagePath->concat(),
        ]);

        return $imagePath;
    }

    public function deleteImage(ImagePath $imagePath): void
    {
        $storage = new StorageClient([
            'projectID' => "",
            'keyFile' => "",
        ]);
        $bucket = $storage->bucket("");
        $object = $bucket->object($imagePath->concat());
        $object->delete();
    }


    public function getImageURL(ImagePath $imagePath): string
    {
        $storage = new StorageClient([
            'projectID' => "",
            'keyFile' => "",
        ]);
        $bucket = $storage->bucket("");
        $object = $bucket->object($imagePath->concat());

        $expire = new \DateTimeImmutable();
        $expire->add(\DateInterval::createFromDateString('1 day'));
        return $object->signedUrl($expire);
    }

}

<?php

namespace App\Domain\ValueObject\Image;

use App\Domain\ValueObject\BaseValueObject;

class ImageURL extends BaseValueObject
{
    public function __construct(public readonly string $url)
    {
        parent::__construct($this->url);
    }
}

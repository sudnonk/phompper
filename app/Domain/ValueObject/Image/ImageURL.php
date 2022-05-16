<?php

namespace App\Domain\ValueObject\Image;

use App\Domain\ValueObject\BaseValueObject;

class ImageURL extends BaseValueObject
{
    public function __construct(public readonly string $value)
    {
        parent::__construct($value);
    }
}

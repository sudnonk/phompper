<?php

namespace App\Domain\ValueObject\Image;

use App\Domain\ValueObject\BaseValueObject;
use App\Domain\ValueObject\ValidatableValueObjectTrait;

class FileHash extends BaseValueObject
{
    use ValidatableValueObjectTrait;

    public const ALGO = "crc32";
    public const LENGTH = 8;

    public function __construct(public readonly string $value)
    {
        parent::__construct($value);
    }

    public static function rule(): array
    {
        return ['required', sprintf('min:%d', self::LENGTH), sprintf('max:%d', self::LENGTH)];
    }

    /**
     * $pathのファイルのハッシュ値をself::ALGOで計算する
     *
     * @param string $path
     * @return static
     */
    public static function fromFile(string $path): self
    {
        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf('%sは読み込めません。', $path));
        }
        return new self(hash_file(self::ALGO, $path));
    }
}

<?php

namespace App\Domain\Rules;

use App\Domain\ValueObject\BuildingName;
use App\Domain\ValueObject\Latitude;
use App\Domain\ValueObject\LineName;
use App\Domain\ValueObject\LineNumber;
use App\Domain\ValueObject\Longitude;
use App\Domain\ValueObject\PositionNote;
use App\Domain\ValueObject\PositionType;
use App\Exceptions\ValidatorInvalidArgumentException;
use Illuminate\Contracts\Validation\Rule;
use JetBrains\PhpStorm\Pure;

class PositionRule implements Rule
{
    protected ?PositionType $type;
    protected string $class;

    /**
     * @param PositionType|null $type
     * @param string            $class 検査対象のクラス名
     */
    protected function __construct(?PositionType $type, string $class)
    {
        $this->type = $type;
        $this->class = $class;
    }

    public function passes($attribute, $value): bool
    {
        try {
            $this->independentsType($value);
            $this->dependsType($value);
            return true;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    public function message(): string
    {
        return match ($this->class) {
            Latitude::class => "経度が範囲外です。",
            Longitude::class => "緯度が範囲外です。",
            PositionType::class => "地点種別が不正です。",
            LineNumber::class => "電信柱と電柱の時、線番は必須です。",
            LineName::class => "電信柱と電柱の時、線名は必須です。",
            BuildingName::class => "通信ビルの時、ビル名は必須です。",
            PositionNote::class => "その他の時、備考は必須です。"
        };
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public function independentsType(mixed $value): bool
    {
        return match ($this->class) {
            Latitude::class => self::latCase($value),
            Longitude::class => self::longCase($value),
            PositionType::class => self::typeCase($value),
            default => false
        };
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public function dependsType(mixed $value): bool
    {
        if ($this->type === null) {
            return false;
        }
        return match ($this->class) {
            LineName::class => self::nameCase($this->type, $value),
            LineNumber::class => self::numberCase($this->type, $value),
            BuildingName::class => self::buildingCase($this->type, $value),
            PositionNote::class => self::noteCase($this->type, $value),
            default => false,
        };
    }

    public static function latRule(): self
    {
        return new self(null, Latitude::class);
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public static function latCase(mixed $value): bool
    {
        new Latitude($value); //VOが作れれば検証通過
        return true;
    }

    public static function longRule(): self
    {
        return new self(null, Longitude::class);
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public static function longCase(mixed $value): bool
    {
        new Longitude($value); //VOが作れれば検証通過
        return true;
    }

    public static function typeRule(): self
    {
        return new self(null, PositionType::class);
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public static function typeCase(mixed $value): bool
    {
        $type = PositionType::tryFrom($value); //VOが作れてnullじゃない(=Enumの中にある)なら検証通過
        return $type !== null;
    }


    #[Pure]
    public static function numberRule(?PositionType $type): self
    {
        return new self($type, LineNumber::class);
    }

    /**
     * @param PositionType $type
     * @param mixed        $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public static function numberCase(PositionType $type, mixed $value): bool
    {
        switch (true) {
            case $type->equals(PositionType::DENSHIN):
            case $type->equals(PositionType::DENCHU):
                new LineNumber($value); //VOが作れれば検証通過
                return true;
            default:
                //地点種別が電信柱と電柱以外の時は線番はnullのはず
                return $value === null;
        }
    }

    #[Pure]
    public static function nameRule(?PositionType $type): self
    {
        return new self($type, LineName::class);
    }

    /**
     * @param PositionType $type
     * @param mixed        $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public static function nameCase(PositionType $type, mixed $value): bool
    {
        switch (true) {
            case $type->equals(PositionType::DENSHIN):
            case $type->equals(PositionType::DENCHU):
                new LineName($value); //VOが作れれば検証通過
                return true;
            default:
                //地点種別が電信柱と電柱以外の時は線名はnullのはず
                return $value === null;
        }
    }

    #[Pure]
    public static function buildingRule(?PositionType $type): self
    {
        return new self($type, BuildingName::class);
    }

    /**
     * @param PositionType $type
     * @param mixed        $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public static function buildingCase(PositionType $type, mixed $value): bool
    {
        switch (true) {
            case $type->equals(PositionType::BUILDING):
                new BuildingName($value); //VOが作れれば検証通過
                return true;
            default:
                //地点種別が通信ビル以外のときはビル名はnullのはず
                return $value === null;
        }
    }

    #[Pure]
    public static function noteRule(?PositionType $type): self
    {
        return new self($type, PositionNote::class);
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws ValidatorInvalidArgumentException
     */
    public static function noteCase(PositionType $type, mixed $value): bool
    {
        $note = new PositionNote($value);
        switch (true) {
            case $type->equals(PositionType::OTHER):
                //VOが作れてかつ1文字以上なら検証通過
                if ($note->getLength() < 1) {
                    return false;
                }
                return true;
            default:
                //地点種別がその他以外の時は0文字でもnullでも良い
                return true;
        }
    }
}

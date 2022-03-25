<?php

namespace App\Domain\ValueObject;

final class Latitude extends BaseValueObject
{
    /**
     * 6桁目まで同じ数字なら同じ緯度とみなす。詳細はequals()のコメントを参照
     *
     * @const PRECISION
     */
    protected const PRECISION = 6;

    protected static $name = "緯度";
    /** @var float $value */
    protected $value;

    public static function rule(): array
    {
        return ['numeric', 'min:-90', 'max:90'];
    }

    public static function message(): array
    {
        return [":inputは:attributeの範囲外です。"];
    }

    /**
     * $valueで与えられた緯度とこのオブジェクトの緯度が同じぐらいかを返す
     * GeoHashのアルゴリズム上完全一致はしないので、小数点以下6桁ぐらいで合えば同じ
     * （35.682506の場合、経度139.76582244504上で35.6825060と35.6825069は0.100(m)の違い）
     *
     * @param float $value
     * @return bool
     */
    public function equals(float $value): bool
    {
        return round($this->value, self::PRECISION) === round($value, self::PRECISION);
    }
}

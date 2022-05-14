<?php

namespace App\Domain\ValueObject\Position;

use App\Domain\ValueObject\BaseValueObject;

final class Longitude extends BaseValueObject
{
    /**
     * 6桁目まで同じ数字なら同じ経度とみなす。詳細はequals()のコメントを参照
     *
     * @const PRECISION
     */
    protected const PRECISION = 6;

    protected static string $name = "経度";

    public static function rule(): array
    {
        return ['numeric', 'min:-180', 'max:180'];
    }

    public static function message(): array
    {
        return [":inputは:attributeの範囲外です。"];
    }

    /**
     * $valueで与えられた経度とこのオブジェクトの経度が同じぐらいかを返す
     * GeoHashのアルゴリズム上完全一致はしないので、小数点以下6桁ぐらいで合えば同じ
     * （139.765822の場合、緯度35.68250605138上で139.7658220と139.7658229は0.081(m)の違い）
     *
     * @param float $value
     * @return bool
     */
    public function equals(float $value): bool
    {
        return round($this->value, self::PRECISION) === round($value, self::PRECISION);
    }
}

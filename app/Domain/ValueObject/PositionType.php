<?php

namespace App\Domain\ValueObject;

final class PositionType extends EnumValueObject
{
    protected static $name = "地点種別";

    const DENSHIN = "電信柱";
    const DENCHU = "電柱";
    const BUILDING = "通信ビル";
    const OTHER = "その他";
}

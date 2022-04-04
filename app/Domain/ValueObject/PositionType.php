<?php

namespace App\Domain\ValueObject;

enum PositionType: string{
    case DENSHIN = "電信柱";
    case DENCHU = "電柱";
    case BUILDING = "通信ビル";
    case OTHER = "その他";

    public function equals(self $value):bool{
        return $this->value === $value->value;
    }
}

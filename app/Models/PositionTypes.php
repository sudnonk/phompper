<?php

namespace App\Models;

use MyCLabs\Enum\Enum;

class PositionTypes extends Enum
{
    const DENSHIN = "電信柱";
    const DENCHU = "電柱";
    const BUILDING = "通信ビル";
    const OTHER = "その他";
}

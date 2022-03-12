<?php

namespace App\Rules;

use App\Models\PositionTypes;
use Illuminate\Contracts\Validation\Rule;

class PositionNumberRule implements Rule
{
    protected $position_type;

    public function __construct(?string $position_type)
    {
        assert(PositionTypes::isValidKey($position_type));
        $this->position_type = $position_type;
    }

    public function passes($attribute, $value): bool
    {
        switch (true) {
            //地点種別が電柱化電信柱なら番号は必須、それ以外はnull
            case PositionTypes::search("電信柱") === $this->position_type:
            case PositionTypes::search("電柱") === $this->position_type:
                return is_string($value);
            default:
                //ミドルウェアで空文字列はnullに変換されるので気にしなくて良い
                return $value === null;
        }
    }

    public function message(): string
    {
        return '地点種別が電信柱か電柱のときは番号が必須です。';
    }

}

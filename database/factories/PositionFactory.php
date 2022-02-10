<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;
use Sk\Geohash\Geohash;

class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Uuid::uuid4(),
            'geohash' => (new Geohash())->encode(35.681217751538604, 139.76709999359113, 24),
            'type'=>'DENSHIN',
            'line'=>'消防支',
            'number'=>'R4/16',
            'name'=>'',//電信柱なので通信ビル名は無い
            'note'=>'テスト用',
        ];
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('positions');
        Schema::create('positions', function (Blueprint $table) {
            //UUID: この地点のID
            $table->string('id')->primary();
            //GeoHASH: 緯度と経度を24文字ぐらいで表せるもの
            $table->string('geohash')->nullable(false);
            //種別: その地点の種別
            $table->string('type')->nullable(false);
            //支線名（電信柱・電柱の場合）
            $table->string('line', 255)->nullable(true);
            //番号（電信柱・電柱の場合）
            $table->string('number', 255)->nullable(true);
            //ビル名（通信ビルの場合）
            $table->string('name', 255)->nullable(true);
            //備考（メモ）
            $table->string('note', 1024);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positions');
    }
}

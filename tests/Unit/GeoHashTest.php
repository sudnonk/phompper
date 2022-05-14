<?php

namespace Tests\Unit;

use App\Domain\ValueObject\Position\GeoHash;
use App\Domain\ValueObject\Position\Latitude;
use App\Domain\ValueObject\Position\Longitude;
use App\Exceptions\ValidatorInvalidArgumentException;
use Tests\TestCase;

class GeoHashTest extends TestCase
{
    protected float $testLat = 35.68250603692052;
    protected float $testLng = 139.7658224841778;

    public function test緯度経度をGeoHashにできる(): string
    {
        $geoHash = GeoHash::fromLatLng(new Latitude($this->testLat), new Longitude($this->testLng));
        self::assertInstanceOf(GeoHash::class, $geoHash);
        return $geoHash->value;
    }

    /**
     * @return void
     * @depends test緯度経度をGeoHashにできる
     */
    public function test正しいGeoHashがパースできる($geoHash)
    {
        $geoHash = new GeoHash($geoHash);
        self::assertTrue($geoHash->latitude->equals($this->testLat));
        self::assertTrue($geoHash->longitude->equals($this->testLng));
    }

    public function test正しい緯度が緯度になる()
    {
        $lat = new Latitude($this->testLat);
        self::assertTrue($lat->equals($this->testLat));
        self::assertIsFloat($lat->value);
    }

    public function test正しい経度が経度になる()
    {
        $lng = new Longitude($this->testLng);
        self::assertTrue($lng->equals($this->testLng));
        self::assertIsFloat($lng->value);
    }

    /**
     * @return void
     */
    public function testおかしいGeoHashはエラーが出る()
    {
        $this->expectException(ValidatorInvalidArgumentException::class);
        $geoHash = new GeoHash('poepoepoepoe~~~');
    }

    public function testおかしい緯度はエラーが出る1(){
        $this->expectException(ValidatorInvalidArgumentException::class);
        $lat = new Latitude('po');
    }
    public function testおかしい緯度はエラーが出る2(){
        $this->expectException(ValidatorInvalidArgumentException::class);
        $lat = new Latitude(-200);
    }
    public function testおかしい緯度はエラーが出る3(){
        $this->expectException(ValidatorInvalidArgumentException::class);
        $lat = new Latitude('po12.1');
    }
    public function testおかしい経度はエラーが出る1(){
        $this->expectException(ValidatorInvalidArgumentException::class);
        $lng = new Longitude('po');
    }
    public function testおかしい経度はエラーが出る2(){
        $this->expectException(ValidatorInvalidArgumentException::class);
        $lng = new Longitude(-200);
    }
    public function testおかしい経度はエラーが出る3(){
        $this->expectException(ValidatorInvalidArgumentException::class);
        $lng = new Longitude('po12.1');
    }
}

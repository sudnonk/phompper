<?php

namespace App\Domain\ValueObject\Position;

use App\Domain\Rules\GeoHashRule;
use App\Domain\ValueObject\BaseValueObject;
use JetBrains\PhpStorm\Pure;

final class GeoHash extends BaseValueObject
{
    /**
     * 24で0.004(m)の誤差
     * 20で0.004(m)の誤差
     * 16で0.008(m)の誤差
     * 12で1.107(m)の誤差
     * ->24ぐらいにする
     *
     * @const PRECISION GeoHashの精度。
     */
    public const PRECISION = 24;

    protected static string $name = "GeoHash";

    public readonly Latitude $latitude;
    public readonly Longitude $longitude;

    /**
     * @param string $value
     */
    public function __construct(public readonly mixed $value)
    {
        parent::__construct($value);
        $latlng = self::parse($this->value);
        $this->latitude = $latlng[0];
        $this->longitude = $latlng[1];
    }

    #[Pure]
    public static function rule(): array
    {
        return [new GeoHashRule()];
    }

    /**
     * @param string $geoHashLike
     * @return array<Latitude,Longitude>
     */
    public static function parse(string $geoHashLike): array
    {
        $latlng = (new \Sk\Geohash\Geohash())->decode($geoHashLike);
        if (isset($latlng[0])) {
            $lat = new Latitude($latlng[0]);
        } else {
            throw new \InvalidArgumentException(sprintf("Failed to parse %s as GeoHash", $geoHashLike));
        }
        if (isset($latlng[1])) {
            $lng = new Longitude($latlng[1]);
        } else {
            throw new \InvalidArgumentException(sprintf("Failed to parse %s as GeoHash", $geoHashLike));
        }

        return [$lat, $lng];
    }

    public static function fromLatLng(Latitude $latitude, Longitude $longitude): self
    {
        $geoHash = (new \Sk\Geohash\Geohash())->encode($latitude->value, $longitude->value, self::PRECISION);
        return new self($geoHash);
    }
}

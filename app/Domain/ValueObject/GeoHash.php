<?php

namespace App\Domain\ValueObject;

use App\Domain\Rules\GeoHashRule;

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
    private const PRECISION = 24;

    protected static $name = "GeoHash";
    /** @var $value string */
    protected $value;

    /** @var Latitude $latitude */
    protected $latitude;
    /** @var Longitude $longitude */
    protected $longitude;

    public function __construct(string $value)
    {
        parent::__construct($value);
        $latlng = self::parse($value);
        $this->latitude = $latlng[0];
        $this->longitude = $latlng[1];
    }

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
        $geoHash = (new \Sk\Geohash\Geohash())->encode($latitude->getValue(), $longitude->getValue(), self::PRECISION);
        return new self($geoHash);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return Latitude
     */
    public function getLatitude(): Latitude
    {
        return $this->latitude;
    }

    /**
     * @return Longitude
     */
    public function getLongitude(): Longitude
    {
        return $this->longitude;
    }
}

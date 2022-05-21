"use strict";
import LatLng = google.maps.LatLng;
import MapMouseEvent = google.maps.MapMouseEvent;
import GMap = google.maps.Map;
import Marker = google.maps.Marker;
import Gcoder = google.maps.Geocoder;
import Geocoder = google.maps.Geocoder;
import PhompperForm from "./phompper_form";
import PhompperUtil from "./phompper_util";

export default class PhompperMap {
    map: GMap | null = this.initGMap();
    geocoder: Gcoder = new Geocoder();
    position: Marker | null = null;
    static defPos: LatLng = new LatLng({lat: 35.681217751538604, lng: 139.76709999359113});

    constructor() {
        const initialPos = this.getUserLocation();
        this.map?.panTo(initialPos);
        this.map?.addListener("click", (mapMouseEvent: MapMouseEvent) => {
            this.moveToPoint(mapMouseEvent);
        });
        this.map?.addListener("center_changed", () => {
            console.log("center_changed");
        });

        this.reMarker(initialPos);
        console.log("Google Mapsを読み込みました。")
    }

    initGMap(): GMap | null {
        const mapDOM: HTMLElement | null = document.getElementById("map");
        if (mapDOM === null) {
            console.error("elementId 'map' not found.");
            return null;
        }
        return new GMap(mapDOM, {
            center: PhompperMap.defPos,
            zoom: 17,
        });

    }

    getGMap(): GMap | null {
        if (this.map === null) {
            console.error("failed to init Google Maps");
            return null;
        }
        return this.map;
    }

    /**
     * クリックされた場所に地図の中心を移動する
     * @param mapMouseEvent
     */
    moveToPoint(mapMouseEvent: MapMouseEvent): void {
        const latlng = mapMouseEvent.latLng;
        if (latlng === null
        ) {
            return;
        }
        const pos = new LatLng(latlng);
        this.reMarker(pos);
        this.map?.panTo(pos);
    }

    /**
     * mapのposの箇所にマーカーを立てる
     * @param pos
     */
    reMarker(pos: LatLng) {
        //現在のマーカーを消す
        if (this.position !== null) {
            this.position.setMap(null);
        }
        this.position = new Marker({position: pos, map: this.map});
        this.getAddress(pos);
        PhompperForm.setLatLng(pos);
    }

    /**
     * ブラウザの地理的位置を取得する
     * @return google.maps.LatLng
     */
    getUserLocation(): LatLng {
        PhompperUtil.showInfo("現在位置を取得しています・・・");
        let pos = PhompperMap.defPos;
        const geolocation = navigator.geolocation;

        const successCallback = (position: GeolocationPosition) => {
            pos = new LatLng({
                lat: position.coords.latitude,
                lng: position.coords.longitude
            });
            PhompperUtil.showInfo("現在位置を取得しました");
        }
        const errorCallback = () => {
            PhompperUtil.showInfo("現在位置の取得に失敗しました。東京を表示します。");
        }
        const option = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        }

        geolocation.getCurrentPosition(successCallback, errorCallback, option);
        return pos;
    }

    /**
     * latlngの場所の住所を取得する
     * @param latlng
     */
    getAddress(latlng: LatLng): void {
        this.geocoder.geocode({
            location: latlng
        })
            .then((results) => {
                const result = results?.results[0]?.formatted_address;
                if (result === null) {
                    throw new Error("取得結果に住所が含まれていません");
                } else {
                    this.setAddress(result.replace(/^日本([、,])/, ''));
                }

            })
            .catch((e) => {
                console.log(e);
                this.setAddress("住所の取得に失敗しました");
            });
    }

    /**
     * HTMLのinputタグ #addressにstrを値として入力する
     * @param str
     */
    setAddress(str: string): void {
        const addrDOM = document.getElementById("address");
        if (addrDOM === null || !(addrDOM instanceof HTMLInputElement)) {
            return;
        }
        addrDOM.value = str;
    }

    /**
     * GoooleMapsの認証に失敗したときに実行される関数
     */
    static gm_authFailure(): void {
        PhompperUtil.showInfo("Google Mapsを読み込めませんでした。");
    }
}

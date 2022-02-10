"use strict";
import LatLng = google.maps.LatLng;
import MapMouseEvent = google.maps.MapMouseEvent;
import GMap = google.maps.Map;
import Marker = google.maps.Marker;
import Gcoder = google.maps.Geocoder;

let map: GMap;
let marker: null | Marker = null;
let geocoder: Gcoder;

interface Window {
    initMap(): void;

    gm_authFailure(): void;
}

declare var window: Window & typeof globalThis;

/**
 * GoogleMapsを初期化する
 */
function initMap(): void {
    //デフォルトでは東京駅を表示する
    const defPos: LatLng = new LatLng({lat: 35.681217751538604, lng: 139.76709999359113});
    const mapDOM: HTMLElement|null = document.getElementById("map");
    if (mapDOM === null) {
        setMapStatus("エラーが発生しました。管理者に連絡してください。");
        return;
    }
    map = new GMap(mapDOM, {
        center: defPos,
        zoom: 17,
    });
    const initialPos = getUserLocation(defPos);
    map.panTo(initialPos);
    reMarker(initialPos, map);
    map.addListener("click", function (mapMouseEvent:MapMouseEvent) {
        moveToPoint(mapMouseEvent);
    });
    map.addListener("center_changed", function () {
        console.log("center_changed");
    });

    geocoder = new Gcoder();
    console.log("Google Mapsを読み込みました。")
}

window.initMap = initMap;

/**
 * クリックされた場所に地図の中心を移動する
 * @param mapMouseEvent
 */
function moveToPoint(mapMouseEvent: MapMouseEvent): void {
    const latlng = mapMouseEvent.latLng;
    if(latlng === null){
        return;
    }
    const pos = new LatLng(latlng);
    reMarker(pos, map);
    map.panTo(pos);
}

/**
 * mapのposの箇所にマーカーを立てる
 * @param pos
 * @param map
 */
function reMarker(pos: LatLng, map: GMap) {
    //現在のマーカーを消す
    if (marker !== null) {
        marker.setMap(null);
    }
    marker = new Marker({position: pos, map: map});
    getAddress(pos);
}

/**
 * ブラウザの地理的位置を取得する
 * @param defaultPosition 取得に失敗した際に表示する場所
 * @return google.maps.LatLng
 */
function getUserLocation(defaultPosition: LatLng): LatLng {
    setMapStatus("現在位置を取得しています・・・");
    let pos = defaultPosition;
    const geolocation = navigator.geolocation;

    const successCallback = function (position: GeolocationPosition) {
        pos = new LatLng({
            lat: position.coords.latitude,
            lng: position.coords.longitude
        });
    }
    const errorCallback = function () {
        setMapStatus("現在位置の取得に失敗しました。東京を表示します。");
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
 *
 * @param latlng
 */
function getAddress(latlng: LatLng): void {
    geocoder.geocode({
        location: latlng
    })
        .then((results) => {
            const result = results?.results[0]?.formatted_address;
            if (result === null) {
                throw new Error("取得結果に住所が含まれていません");
            } else {
                setAddress(result.replace(/^日本([、,])/, ''));
            }

        })
        .catch((e) => {
            console.log(e);
            setAddress("住所の取得に失敗しました");
        });
}

/**
 * GoogleMapsの状態を表示する
 * @param str
 */
function setMapStatus(str: string): void {
    console.log(str);
    const infoDOM = document.getElementById("map_status");
    if (infoDOM === null) {
        return;
    }
    infoDOM.innerText = str;
}

/**
 *
 * @param str
 */
function setAddress(str:string):void{
    const addrDOM = document.getElementById("address");
    if(addrDOM === null){
        return;
    }
    addrDOM.innerText = str;
}

/**
 * GoooleMapsの認証に失敗したときに実行される関数
 */
function gm_authFailure(): void {
    setMapStatus("Google Mapsを読み込めませんでした。");
}

window.gm_authFailure = gm_authFailure;

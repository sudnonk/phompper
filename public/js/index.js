"use strict";
let map;
let marker = null;

/**
 * GoogleMapsを初期化する
 */
function initMap() {
    //デフォルトでは東京駅を表示する
    const defPos = new google.maps.LatLng({lat: 35.681217751538604, lng: 139.76709999359113});
    const mapDOM = document.getElementById("map");
    if (mapDOM === null) {
        setMapStatus("エラーが発生しました。管理者に連絡してください。");
        return;
    }
    map = new google.maps.Map(mapDOM, {
        center: defPos,
        zoom: 17,
    });
    const initialPos = getUserLocation(defPos);
    map.panTo(initialPos);
    reMarker(initialPos, map);
    map.addListener("click", function (mapMouseEvent) {
        moveToPoint(mapMouseEvent);
    });
    map.addListener("center_changed", function () {
        console.log("center_changed");
    });
    console.log("Google Mapsを読み込みました。")
}

window.initMap = initMap;

/**
 * クリックされた場所に地図の中心を移動する
 * @param mapMouseEvent
 */
function moveToPoint(mapMouseEvent) {
    const lat = mapMouseEvent.latLng.lat();
    const lng = mapMouseEvent.latLng.lng();
    console.log(lat, lng);
    if (lat === undefined || lng === undefined) {
        return;
    }
    const pos = new google.maps.LatLng({lat: lat, lng: lng});
    reMarker(pos, map);
    map.panTo(pos);
}

/**
 * mapのposの箇所にマーカーを立てる
 * @param pos
 * @param map
 */
function reMarker(pos, map) {
    //現在のマーカーを消す
    if (marker !== null) {
        marker.setMap(null);
    }
    marker = new google.maps.Marker({position: pos, map: map});
    getAddress(pos);
}

/**
 * ブラウザの地理的位置を取得する
 * @param defaultPosition 取得に失敗した際に表示する場所
 * @return {*}
 */
function getUserLocation(defaultPosition) {
    setMapStatus("現在位置を取得しています・・・");
    let pos = defaultPosition;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            pos = new google.maps.LatLng({
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            });
        });
    }
    if (pos === defaultPosition) {
        setMapStatus("現在位置の取得に失敗しました。東京を表示します。");
        return defaultPosition;
    } else {
        return pos;
    }
}

/**
 * latlngの場所の住所を取得して表示する
 * @param latlng
 */
function getAddress(latlng) {
    const geocoder = new google.maps.Geocoder();

    geocoder.geocode({
        latLng: latlng
    }, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[0].geometry) {
                document.getElementById("address").innerText = results[0].formatted_address.replace(/^日本(、|,)/, '');
            }
        } else {
            document.getElementById("address").innerText = "住所の取得に失敗しました";
        }
    });
}

/**
 * GoogleMapsの状態を表示する
 * @param str
 */
function setMapStatus(str) {
    console.log(str);
    const infoDOM = document.getElementById("map_status");
    if (infoDOM === null) {
        return;
    }
    infoDOM.innerText = str;
}

/**
 * GoooleMapsの認証に失敗したときに実行される関数
 */
function gm_authFailure() {
    setMapStatus("Google Mapsを読み込めませんでした。");
}

window.gm_authFailure = gm_authFailure;

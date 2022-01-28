"use strict";
let map;
let marker = null;

function initMap() {
    const defPos = new google.maps.LatLng({lat: 35.681217751538604, lng: 139.76709999359113});
    const mapDOM = document.getElementById("map");
    if (mapDOM === null) {
        setInfo("エラーが発生しました。管理者に連絡してください。");
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
}

window.initMap = initMap;

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

function reMarker(pos, map) {
    if (marker !== null) {
        marker.setMap(null);
    }
    marker = new google.maps.Marker({position: pos, map: map});
}

function getUserLocation(defaultPosition) {
    setInfo("現在位置を取得しています・・・");
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
        setInfo("現在位置の取得に失敗しました。東京を表示します。");
        return defaultPosition;
    } else {
        return pos;
    }
}

function setInfo(str) {
    console.log(str);
    const infoDOM = document.getElementById("info");
    if (infoDOM === null) {
        return;
    }
    infoDOM.innerText = str;
}

function gm_authFailure() {
    setInfo("Google Mapsを読み込めませんでした。");
}

window.gm_authFailure = gm_authFailure;

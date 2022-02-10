<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>テスト</title>
    <link rel="stylesheet" type="text/css"  href="{{asset('/css/main.css')}}">
</head>
<body>
<p id="map_status">地図を読み込んでいます・・・</p>
<div id="map"></div>
<p id="address">住所の読み込みに失敗しました</p>
</body>
<script src="{{asset('/js/googlemaps.js')}}"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap.api-key')}}&callback=initMap&v=weekly"
    async
></script>
</html>

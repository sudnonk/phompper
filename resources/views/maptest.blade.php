<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>テスト</title>
</head>
<body>
<p id="info">地図を読み込んでいます・・・</p>
<div id="map"></div>
</body>
<script src="{{asset('/js/index.js',true)}}"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap.api-key')}}&callback=initMap&libraries=&v=weekly"
    async
></script>
</html>

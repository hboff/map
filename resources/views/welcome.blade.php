<!DOCTYPE html>
<html>
<head>
    <title>City Map</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}"/>
    <style>
        #map {
            height: 100vh;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script src="{{ asset('js/leaflet.js') }}"></script>
<script>
    var geojson = {!! json_encode($geojson) !!};

    var map = L.map('map').setView([51.505, 10], 6);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'your.mapbox.access.token'
    }).addTo(map);

    L.geoJSON(geojson, {
        style: function (feature) {
            return {color: 'red'};
        },
        onEachFeature: function (feature, layer) {
            layer.bindPopup(feature.properties.name);
        }
    }).addTo(map);
</script>
</body>
</html>


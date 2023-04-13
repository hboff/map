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
<div class="container">
    <h1>Großstädte in Deutschland</h1>
    <div id="map"></div>
</div>

<script>
// Initialisieren Sie die Leaflet-Karte
var map = L.map('map').setView([51.1657, 10.4515], 6);

// Fügen Sie eine OpenStreetMap-Kachel hinzu
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox.streets'
}).addTo(map);

// Rufen Sie die Polygone über Ajax auf und fügen Sie sie zur Karte hinzu
$.ajax({
    url: '/polygons',
    dataType: 'json',
    success: function(data) {
        for (var i = 0; i < data.length; i++) {
            L.polygon(data[i]).addTo(map);
        }
    }
});
</script>

</body>
</html>

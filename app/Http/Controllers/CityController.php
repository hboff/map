<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phayes\GeoPHP\GeoPHP;
use GeoJSON\Feature\Feature;
use GeoJSON\Feature\FeatureCollection;
use GeoJSON\Geometry\LinearRing;
use GeoJSON\Geometry\MultiPolygon;
use GeoJSON\Geometry\Polygon;

class CityController extends Controller
{
    public function map()
    {
        // Lade die Daten der Großstädte in Deutschland
        $cities = DB::table('cities')->select(DB::raw('laenge, breite'))->get();

        // Wähle die Merkmale aus
        $points = [];
        foreach ($cities as $city) {
            $points[] = [$city->breite, $city->laenge];
        }

        // Führe die Delaunay-Triangulation durch
        $triangles = GeoPHP::delaunayTriangulation($points);

        // Erstelle die Polygone
        $polygons = [];
        foreach ($triangles as $triangle) {
            $vertices = $triangle->getVertices();
            $ring = [];
            foreach ($vertices as $vertex) {
                $ring[] = [$vertex->y(), $vertex->x()];
            }
            $polygons[] = new Polygon(new LinearRing($ring));
        }
        $multiPolygon = new MultiPolygon($polygons);

        // Ordne jeder Stadt das zugehörige Dreieck zu
        $features = [];
        foreach ($cities as $city) {
            $point = GeoPHP::load("POINT($city->longitude $city->latitude)");
            $polygon = $multiPolygon->contains($point) ? $multiPolygon->getPolygonContaining($point) : null;
            $feature = new Feature(new Polygon(new LinearRing($polygon->getVertices())), ['name' => $city->name]);
            $features[] = $feature;
        }
        $featureCollection = new FeatureCollection($features);

        // Gebe die Karte zurück
        return view('map', compact('featureCollection'));
    }
}

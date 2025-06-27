<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;

class MapsController extends Controller {
    private const GOOGLE_TOKEN = "AIzaSyCo2JgylZ5y7HgfRuLNOAZinWwtQvJLfOQ";
    private const GOOGLE_ROUTES_API_URI = "https://routes.googleapis.com/directions/v2:computeRoutes";
    private const HEADERS = [
        "Accept: application/json",
        "X-Goog-Api-Key: " . self::GOOGLE_TOKEN,
        "Content-Type: application/json",
        "X-Goog-FieldMask: routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline"
    ];

    public static function getAddressData($latitude, $longitud) {
        $uri = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitud&key=" . self::GOOGLE_TOKEN;
        $headers = [
            'content-type' => 'application/json',
            'accept' => 'application/json'
        ];
        $purchase = curl_init($uri);
        curl_setopt($purchase, CURLOPT_URL, $uri);
        curl_setopt($purchase, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($purchase, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($purchase);
        curl_close($purchase);
        return json_decode($resp, true);
    }

    public static function getRouteData($sellerAddress, $buyerAddress) {
        $response = self::buildRouteData($sellerAddress, $buyerAddress);
        return self::parseData($response);
    }

    private static function buildRouteData($sellerAddress, $buyerAddress) {
        $data = [
            'origin' => self::buildOrigin($sellerAddress),
            'destination' => self::buildDestination($buyerAddress),
            'travelMode' => 'DRIVE',
            'routingPreference' => 'TRAFFIC_AWARE',
            'computeAlternativeRoutes' => false,
            'routeModifiers' => [
                'avoidTolls' => false,
                'avoidHighways' => false,
                'avoidFerries' => false
            ],
            'languageCode' => 'es-MX',
            'units' => 'IMPERIAL'
        ];
        return self::makeRequest(json_encode($data));
    }

    private static function buildOrigin($sellerAddress) {
        return [
            'location' => [
                'latLng' => [
                    'latitude' => $sellerAddress->latitude,
                    'longitude' => $sellerAddress->longitude
                ]
            ]
        ];
    }

    private static function buildDestination($buyerAddress) {
        return [
            'location' => [
                'latLng' => [
                    'latitude' => $buyerAddress->latitude,
                    'longitude' => $buyerAddress->longitude
                ]
            ]
        ];
    }

    private static function makeRequest($data) {
        $purchase = curl_init(self::GOOGLE_ROUTES_API_URI);
        curl_setopt($purchase, CURLOPT_URL, self::GOOGLE_ROUTES_API_URI);
        curl_setopt($purchase, CURLOPT_POST, true);
        curl_setopt($purchase, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($purchase, CURLOPT_HTTPHEADER, self::HEADERS);
        curl_setopt($purchase, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($purchase);
        curl_close($purchase);
        return json_decode($resp, true);
    }

    private static function parseData($response) {
        $distanceInKm = (int)$response['routes'][0]['distanceMeters'] / 1000;
        $durationInMinutes = (int)str_replace('s', '', $response['routes'][0]['duration']) / 60;
        $data = [
            'distance' => $distanceInKm,
            'duration' => round($durationInMinutes)
        ];
        return $data;
    }
}

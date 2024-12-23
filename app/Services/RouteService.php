<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class RouteService
{
    public static function calculateRoute(array $coordinates)
    {
        $url = 'https://api.openrouteservice.org/v2/directions/driving-car';

        $response = Http::withHeaders(['Authorization' =>  env('LEAF_MAP_KEY')])
            ->post($url, [
                'coordinates' => $coordinates,
            ]);

        if ($response->successful() && isset($response->json()['routes'][0])) {
            $route = $response->json()['routes'][0];
            return [
                'distance' => $route['summary']['distance'] / 1000, // km
                'duration' => $route['summary']['duration'] / 60,   // minutes
            ];
        }

        return null;
    }
}

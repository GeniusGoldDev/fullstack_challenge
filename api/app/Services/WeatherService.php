<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    /**
     * Fetches the weather data for a given latitude and longitude.
     *
     * @param float $latitude
     * @param float $longitude
     * @return array|null
     */
    public static function fetch(float $latitude, float $longitude): ?array
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $latitude,
            'lon' => $longitude,
            // 'units' => 'metric',
            'appid' => env('OPENWEATHER_API_KEY'),
        ]);

        if ($response->ok()) {
            return $response->json();
        } else {
            return null;
        }
    }
}

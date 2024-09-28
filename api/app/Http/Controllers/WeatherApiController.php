<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Services\WeatherService;



class WeatherApiController extends Controller
{
    public function getWeather(User $user)
    {
        $cacheKey = 'weather-' . $user->id;

        //check if the weather data for this user is already present in the cache, and return it if it is.
        $weatherData = Cache::get($cacheKey);

        //Otherwise, retrieve the weather data from the OpenWeatherMap API
        if (!$weatherData) {

            $weatherData = WeatherService::fetch($user->latitude, $user->longitude);
            
        //cache if response from API is successfull otherwise abort
            if ($weatherData) {
                Cache::put($cacheKey, $weatherData, now()->addMinutes(60));
            } else {
                abort(response('No Weather Data', 404));
            }
        }

        return $weatherData;

    }
}

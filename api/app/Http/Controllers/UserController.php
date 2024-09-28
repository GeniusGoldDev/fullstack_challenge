<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Jobs\GetWeatherJob;




class UserController extends Controller
{
    public function getUsers()
    {

        $users = User::all();

        foreach ($users as $user) {
            $cacheKey = 'weather-' . $user->id;

            // Check if weather data doesnt exists in cache
            
            if (!Cache::has($cacheKey)) {
                // Queue a job to fetch weather data
                dispatch(new GetWeatherJob($user));
            }
        }

        return response()->json([
            'message' => 'all systems are a go',
            'users' => $users,
        ]);
    }
}

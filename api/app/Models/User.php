<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use App\Services\WeatherService;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getWeather()
    {
        $cacheKey = 'weather-' . $this->id;

        // Check if weather data exists in cache
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Fetch weather data from API
        $weather = WeatherService::fetch($this->latitude, $this->longitude);

        // Cache weather data for 1 hour
        Cache::put($cacheKey, $weather, 60 * 60);

        return $weather;
    }
}

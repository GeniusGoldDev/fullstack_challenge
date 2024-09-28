<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;




class WeatherTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_users_returns_data(): void
    {
        User::factory(20)->create();

        // Select a random test user
        $user = User::inRandomOrder()->first();

        // Make a request to the getUsers endpoint
        $response = $this->get('/');

        // Assert that the response has a success status code
        $response->assertStatus(200);

        // Assert that the response contains the test user's data
        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
        ]);
    }

    public function test_get_weather_returns_data()
    {
        User::factory(2)->create();

        // Select a random test user
        $randomUser = User::inRandomOrder()->first();

        // Call the getWeather method for the test user
        $response = $this->get("/weather/{$randomUser->id}");

        // Check that the response is successful and contains the expected weather data
        $response->assertOk();
        $response->assertJsonStructure([
            'coord',
            'weather',
            'base',
            'main',
            'visibility',
            'wind',
            'clouds',
            'dt',
            'sys',
            'timezone',
            'id',
            'name',
            'cod',
        ]);
    }
    public function test_weather_api_request_should_not_exceed_500ms()
    {
        User::factory(2)->create();
    
        // Make a request to the getUsers endpoint which will also trigger the job
        $response = $this->get('/');
    
        $delayInSeconds = 5;
    
        // Wait for the job to finish processing
        sleep($delayInSeconds);
    
        // Select a random test user
        $randomUser = User::inRandomOrder()->first();
    
       if($this->isQueueWorkerRunning()){
         // Send the API request and measure the response time
         $start = microtime(true);
         // Call the getWeather method for the test user
         $response = $this->get("/weather/{$randomUser->id}");
         $end = microtime(true);
     
         // Assert that the response time is less than or equal to 500ms
         $this->assertLessThanOrEqual(500, ($end - $start) * 1000, 'The API request exceeded 500ms.');
       }
    
    }
    

    public function isQueueWorkerRunning()
    {
        $output = null;
        exec("ps aux | grep 'queue:work'", $output);

        // Check if the output contains a process with the 'queue:work' command
        foreach ($output as $line) {
            if (strpos($line, 'queue:work') !== false) {
                return true;
            }
        }

        // If no matching process was found, return false
        return false;
    }

}

<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenWeatherService
{
    protected string $apiKey = '582ac17e08e59816475a39baff057e45';

    public function getWeather(float $lat, float $lon): ?array
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'id',
        ]);

        return $response->successful() ? $response->json() : null;
    }
}

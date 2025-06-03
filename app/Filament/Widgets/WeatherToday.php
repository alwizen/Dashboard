<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\Http;
use Filament\Widgets\Widget;
use App\Services\OpenWeatherService;

class WeatherToday extends Widget
{
    protected static string $view = 'filament.widgets.weather-today';

    protected static ?string $heading = 'Cuaca Hari Ini';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '300s';

    protected static ?int $sort = 0;

    public ?array $data = [];

    public function mount(): void
    {
        $this->loadWeatherData();
    }

    public function loadWeatherData(): void
    {
        $this->data = (new OpenWeatherService())->getWeather(-6.869723, 109.186403);
    }
}
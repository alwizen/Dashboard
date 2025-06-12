<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\Http;
use Filament\Widgets\Widget;
use App\Services\OpenWeatherService;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class WeatherToday extends Widget
{
    use HasWidgetShield;
    
    protected static string $view = 'filament.widgets.weather-today';

    protected static ?string $heading = 'Cuaca Hari Ini';

    // protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '300s';

//    protected int | string | array $columnSpan = 1;

    protected static ?int $sort = 0;

    public ?array $data = [];

    public function mount(): void
    {
        $this->loadWeatherData();
    }

    public function loadWeatherData(): void
    {
        $this->data = (new OpenWeatherService())->getWeather(-6.869723, 109.186403);

        if (!$this->data) {
            $this->data = [
                'error' => 'Gagal memuat data cuaca. Silakan coba lagi nanti.',
            ];
        }
    }
}

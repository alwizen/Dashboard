<x-filament::widget>
    <x-filament::card wire:poll.300s>
        <h2 class="text-lg font-bold mb-4">Cuaca Hari Ini - Fuel Terminal Tegal</h2>

        @if ($data && !isset($data['error']))
            <div class="flex items-center gap-6">
                @php
                    $icon = $data['weather'][0]['icon'] ?? null;
                    $description = $data['weather'][0]['description'] ?? '-';
                    $temp = $data['main']['temp'] ?? '-';
                    $feels_like = $data['main']['feels_like'] ?? '-';
                    $humidity = $data['main']['humidity'] ?? '-';
                    $pressure = $data['main']['pressure'] ?? '-';
                    $wind_speed = $data['wind']['speed'] ?? '-';
                    $wind_deg = $data['wind']['deg'] ?? '-';
                    $visibility = isset($data['visibility']) ? $data['visibility'] / 1000 : '-';
                    $weather_main = $data['weather'][0]['main'] ?? '-';
                    $location_name = $data['name'] ?? '-';
                    $country = $data['sys']['country'] ?? '-';
                @endphp

                @if ($icon)
                    <img src="https://openweathermap.org/img/wn/{{ $icon }}@2x.png" alt="Ikon Cuaca" class="w-20 h-20">
                @endif

                <div class="w-full">
                    <p class="text-xl font-semibold capitalize mb-2">
                        {{ $description }}
                    </p>

                    <ul class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm">
                        <li><strong>Suhu:</strong> {{ $temp }}°C (Terasa: {{ $feels_like }}°C)</li>
                        <li><strong>Kelembaban:</strong> {{ $humidity }}%</li>
                        <li><strong>Tekanan Udara:</strong> {{ $pressure }} hPa</li>
                        <li><strong>Kecepatan Angin:</strong> {{ $wind_speed }} m/s</li>
                        <li><strong>Arah Angin:</strong> {{ $wind_deg }}°</li>
                        <li><strong>Jarak Pandang:</strong> {{ $visibility }} km</li>
                        <li><strong>Cuaca:</strong> {{ $weather_main }}</li>
                        <li><strong>Lokasi:</strong> {{ $location_name }}, {{ $country }}</li>
                    </ul>
                </div>
            </div>
        @elseif(isset($data['error']))
            <p class="text-red-500">{{ $data['error'] }}</p>
        @else
            <p class="text-red-500">Gagal mengambil data cuaca.</p>
        @endif

        <br>
    </x-filament::card>
</x-filament::widget>

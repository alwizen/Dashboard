<x-filament::widget>
    <x-filament::card wire:poll.300s>
        <h2 class="text-lg font-bold mb-4">Cuaca Hari Ini - Fuel Terminal Tegal</h2>

        {{-- <x-filament::button wire:click="loadWeatherData" color="primary" size="sm">
            Refresh Cuaca
        </x-filament::button>
        <br><br> --}}
       

        @if ($data)
            <div class="flex items-center gap-6">
                <img src="https://openweathermap.org/img/wn/{{ $data['weather'][0]['icon'] }}@2x.png" alt="Ikon Cuaca"
                    class="w-20 h-20">
                <div class="w-full">
                    <p class="text-xl font-semibold capitalize mb-2">
                        {{ $data['weather'][0]['description'] }}
                    </p>

                    <ul class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm">
                        <li><strong>Suhu:</strong> {{ $data['main']['temp'] }}°C (Terasa:
                            {{ $data['main']['feels_like'] }}°C)</li>
                        <li><strong>Kelembaban:</strong> {{ $data['main']['humidity'] }}%</li>
                        <li><strong>Tekanan Udara:</strong> {{ $data['main']['pressure'] }} hPa</li>
                        <li><strong>Kecepatan Angin:</strong> {{ $data['wind']['speed'] }} m/s</li>
                        <li><strong>Arah Angin:</strong> {{ $data['wind']['deg'] }}°</li>
                        <li><strong>Jarak Pandang:</strong> {{ $data['visibility'] / 1000 }} km</li>
                        <li><strong>Cuaca:</strong> {{ $data['weather'][0]['main'] }}</li>
                        <li><strong>Lokasi:</strong> {{ $data['name'] }}, {{ $data['sys']['country'] }}</li>
                    </ul>
                </div>
            </div>
        @else
            <p class="text-red-500">Gagal mengambil data cuaca.</p>
        @endif
        <br>
       

    </x-filament::card>
</x-filament::widget>

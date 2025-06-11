<x-filament::widget>
    <x-filament::card>
        <div class="text-xl font-semibold flex items-center">
            🛠️ {{ $inspectedCount }} dari {{ $totalTanker }} Mobil Tangki Sudah Uji Alpukat
            
            {{-- Status Badge berdasarkan persentase --}}
            @if($percentage >= 80)
                <span class="ml-3 px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                    Excellent
                </span>
            @elseif($percentage >= 60)
                <span class="ml-3 px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                    Good
                </span>
            @elseif($percentage >= 40)
                <span class="ml-3 px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                    Fair
                </span>
            @else
                <span class="ml-3 px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                    Poor
                </span>
            @endif
        </div>

        <div class="text-sm text-gray-500 mt-1 mb-10">
            Total mobil tangki terdaftar: <strong>{{ $totalTanker }}</strong><br>
            Belum Uji Alpukat: <strong>{{ $totalTanker - $inspectedCount }}</strong> mobil<br>
            Persentase inspeksi: <strong>{{ $percentage }}%</strong>
        </div><br>

        <div class="mt-4">
            <div class="w-full bg-gray-200 rounded-full h-6 dark:bg-gray-700 overflow-hidden shadow-inner relative">
                <div
                    class="h-6 transition-all duration-700 ease-out relative overflow-hidden rounded-full"
                    style="width: {{ $percentage }}%; background-color: {{ $progressColor }};"
                >
                    {{-- Efek shimmer/shine pada progress bar --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 
                               transform -skew-x-12 animate-pulse"></div>
                    
                    {{-- Text persentase di dalam progress bar --}}
                    @if($percentage > 15)
                        <div class="absolute inset-0 flex items-center justify-center text-white text-sm font-semibold drop-shadow">
                            {{ $percentage }}%
                        </div>
                    @endif
                </div>
                
                {{-- Text persentase di luar progress bar jika terlalu kecil --}}
                @if($percentage <= 15 && $percentage > 0)
                    <div class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-semibold">
                    </div>
                @endif
            </div>
        </div>

        {{-- Indikator untuk menunjukkan kapan terakhir diupdate --}}
        {{-- <div class="mt-3 text-xs text-gray-400 flex items-center justify-between">
            <span>Data diperbarui otomatis setiap 30 detik</span>
            <span class="flex items-center">
                <svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Auto-refresh
            </span>
        </div> --}}
    </x-filament::card>
</x-filament::widget>
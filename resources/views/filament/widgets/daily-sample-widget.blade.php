<x-filament::widget>
    <x-filament::card wire:poll.5s>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Retain Sample</h2>
            <span class="text-sm text-gray-600">{{ now()->format('d M Y') }}</span>
        </div>
        
        <div class="grid grid-cols-2 gap-6 items-start">
            <!-- List Produk -->
            <div class="text-sm space-y-2">
                @foreach ($this->dailySample?->dailySampleItems ?? [] as $item)
                    <div class="flex justify-between items-center">
                        <span class="flex-shrink-0">• {{ $item->product->name }}</span>
                        <span class="text-right ml-4">
                            : {{ number_format($item->dencity, 3) }}/{{ $item->temperature . ' °C' }} – {{ $item->nil_water ? 'Nil Water' : '-' }}
                        </span>
                    </div>
                @endforeach
            </div>
            
            <!-- Gambar -->
            <div class="flex justify-center items-start">
                @if ($this->dailySample?->photo)
                    <img src="{{ Storage::url($this->dailySample->photo) }}" 
                         class="w-40 h-32 rounded shadow object-cover" 
                         alt="Retain Sample Photo">
                @else
                    <div class="w-40 h-32 bg-gray-100 rounded shadow flex items-center justify-center">
                        <span class="text-gray-400 text-center text-xs">No photo available</span>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
<x-filament::widget>
    <x-filament::card wire:poll.5s>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Retain Sample</h2>
            <span class="text-sm text-gray-600">{{ now()->format('d M Y') }}</span>
        </div>

        <div class="grid grid-cols-2 gap-6 items-start">
            <!-- List Produk -->
            <div class="text-sm space-y-1">
                @foreach ($this->dailySample?->dailySampleItems ?? [] as $item)
                <div class="grid grid-cols-2 gap-6 items-start">                        
                    <span>• {{ $item->product->name }}</span>
                    <span>: {{ number_format($item->dencity, 3) }}/{{ $item->temperature . ' °C' }} – {{ $item->nil_water ? 'Nil Water' : '-' }}</span>
                </div>
                @endforeach
            </div>

            <!-- Gambar -->
            <div class="flex justify-center">
                @if ($this->dailySample?->photo)
                    <img src="{{ Storage::url($this->dailySample->photo) }}" class="w-60 rounded shadow" alt="Retain Sample Photo">
                @else
                    <span class="text-gray-400">No photo available</span>
                @endif
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>

<div class="space-y-4">
    @foreach (['photo1', 'photo2', 'photo3'] as $photo)
        @if ($record->$photo)
            <div>
                <div class="font-semibold mb-1 capitalize">{{ $photo }}</div>
                <img src="{{ Storage::url($record->$photo) }}" alt="{{ $photo }}" class="rounded-md w-full max-w-md">
            </div>
        @endif
    @endforeach
</div>

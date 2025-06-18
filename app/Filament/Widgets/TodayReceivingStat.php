<?php

namespace App\Filament\Widgets;

use App\Models\ReceivingItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Collection;

class TodayReceivingStat extends BaseWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'CR - Penerimaan Hari ini';

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $items = ReceivingItem::with('product')->get();

        // Kelompokkan berdasarkan produk dan jumlahkan value-nya
        $grouped = $items->groupBy('product.name')->map(function ($items) {
            return $items->sum('value');
        });

        // Buat array Stat untuk masing-masing produk
        $stats = [];

        foreach ($grouped as $product => $total) {
            $stats[] = Stat::make($product, number_format($total, 2) . ' Kl');
        }

        return $stats;
    }
}

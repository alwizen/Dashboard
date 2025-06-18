<?php

namespace App\Filament\Widgets;

use App\Models\DistributionItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodayDistributionStat extends BaseWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'DIST - Penyaluran Hari ini';

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $items = DistributionItem::with('product')->get();

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

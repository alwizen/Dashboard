<?php

namespace App\Filament\Widgets;

use App\Models\DistributionItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TodayDistributionStat extends BaseWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'DIST - Penyaluran Hari ini';

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $items = DistributionItem::with('product')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $grouped = $items->groupBy('product.name')->map(fn ($items) => $items->sum('value'));
        $totalAll = $grouped->sum();

        $stats = [
            Stat::make('Total Penyaluran', number_format($totalAll, 2) . ' Kl')
                ->description('Jumlah keseluruhan hari ini')
                ->icon('heroicon-o-chart-bar')
                ->color('primary'),
        ];

        // Tambahkan per produk
        foreach ($grouped as $product => $total) {
            $stats[] = Stat::make($product, number_format($total, 2) . ' Kl')
                ->description('Penyaluran produk ' . $product)
                ->icon('heroicon-o-cube') // ganti sesuai ikon produk kalau perlu
                ->color('success'); // atau bisa dibuat dinamis berdasarkan produk
        }

        return $stats;
    }
}

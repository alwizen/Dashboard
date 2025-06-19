<?php

namespace App\Filament\Widgets;

use App\Models\ReceivingItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TodayReceivingStat extends BaseWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'CR - Penerimaan Hari ini';

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $items = ReceivingItem::with('product')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $grouped = $items->groupBy('product.name')->map(fn ($items) => $items->sum('value'));
        $totalAll = $grouped->sum();

        // $stats = [
        //     Stat::make('Total Penerimaan', number_format($totalAll, 2) . ' Kl')
        //         ->description('Jumlah keseluruhan hari ini')
        //         ->icon('heroicon-o-inbox')
        //         ->color('primary'),
        // ];

        foreach ($grouped as $product => $total) {
            $stats[] = Stat::make($product, number_format($total, 2) . ' Kl')
                ->description('Penerimaan produk ' . $product)
                ->icon('heroicon-o-cube')
                ->color('success');
        }

        return $stats;
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\DailyReportTanker;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TankerToday extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Mobil Tangki Overview';

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $today = Carbon::today();

        $report = DailyReportTanker::whereDate('report_date', $today)->first();

        // Jika tidak ada data hari ini, tampilkan nol atau fallback
        $total = $report->count_tankers ?? 0;
        $maintenance = $report->count_tanker_under_maintenance ?? 0;
        $afkir = $report->count_tanker_afkir ?? 0;
        $available = $report->count_tanker_available ?? 0;

        // Contoh: kapasitas tetap diambil dari model Tanker realtime
        $totalCapacityAvailable = \App\Models\Tanker::where('status', 'available')->sum('capacity');

        return [
            Stat::make('Total Tankers', $total)
                ->description('Total Mobil Tangki FT Tegal')
                ->icon('heroicon-o-truck')
                ->color('info')
                ->color('primary'),

            Stat::make('Under Maintenance', $maintenance)
                ->description('MT dalam Perbaikan')
                ->color('warning')
                ->icon('heroicon-o-wrench'),

            Stat::make('AFKIR', $afkir)
                ->description('MT AFKIR')
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),

            Stat::make('Available', "{$available} MT / {$totalCapacityAvailable} KL")
                ->description('MT Siap Operasional')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
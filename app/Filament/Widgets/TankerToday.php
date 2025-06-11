<?php

namespace App\Filament\Widgets;

use App\Models\DailyReportTanker;
use App\Models\Tanker;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TankerToday extends BaseWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Mobil Tangki Overview';

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $today = Carbon::today();

        $report = DailyReportTanker::whereDate('report_date', $today)->first();

        // Ambil total tanker dari tabel Tanker langsung
        $total = Tanker::count();

        $maintenance = $report->count_tanker_under_maintenance ?? 0;
        $afkir = $report->count_tanker_afkir ?? 0;
        $available = $report->count_tanker_available ?? 0;

        $totalCapacityAvailable = Tanker::where('status', 'available')->sum('capacity');

        return [
            Stat::make('Total Mobil Tangki', $total)
                ->description('Total Mobil Tangki FT Tegal')
                ->icon('heroicon-o-truck')
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

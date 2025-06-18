<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceAmt;
use App\Models\DriverAmt;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AmtAttendanceStat extends BaseWidget
{
    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '5s';

    protected int $columns = 2;

    protected ?string $heading = 'Kehadiran AMT Safety Talk Hari ini';

    protected function getStats(): array
    {
        $today = Carbon::today();

        // AMT 1
        $amt1Total = DriverAmt::where('position', 'AMT 1')->count();
        $amt1Hadir = DriverAmt::where('position', 'AMT 1')
            ->whereHas('attendances', fn($q) => $q->whereDate('scanned_at', $today))
            ->count();
        $amt1Persen = $amt1Total > 0 ? round(($amt1Hadir / $amt1Total) * 100) : 0;

        // AMT 2
        $amt2Total = DriverAmt::where('position', 'AMT 2')->count();
        $amt2Hadir = DriverAmt::where('position', 'AMT 2')
            ->whereHas('attendances', fn($q) => $q->whereDate('scanned_at', $today))
            ->count();
        $amt2Persen = $amt2Total > 0 ? round(($amt2Hadir / $amt2Total) * 100) : 0;

        // Total
        $totalAmt = $amt1Total + $amt2Total;
        $totalHadir = $amt1Hadir + $amt2Hadir;
        $totalBelum = $totalAmt - $totalHadir;
        $totalPersen = $totalAmt > 0 ? round(($totalHadir / $totalAmt) * 100) : 0;

        return [
            Stat::make('Total Hadir', "{$totalHadir} dari {$totalAmt} ({$totalPersen}%)")
                ->description('Jumlah total kehadiran hari ini')
                ->descriptionIcon('heroicon-m-percent-badge')
                ->color('primary'),

            Stat::make('Total Belum Hadir', "{$totalBelum} dari {$totalAmt}")
                ->description('AMT belum hadir')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('AMT 1 Hadir', "{$amt1Hadir} dari {$amt1Total}")
                ->description('Jumlah AMT 1 hadir hari ini')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('AMT 2 Hadir', "{$amt2Hadir} dari {$amt2Total}")
                ->description('Jumlah AMT 2 hadir hari ini')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}

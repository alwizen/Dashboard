<?php

namespace App\Filament\Widgets;

use App\Models\Cctv as ModelsCctv;
use App\Models\DailyCctvReport;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Cctv extends BaseWidget
{
    use HasWidgetShield;
    /**
     * The name of the widget.
     *
     * @var string
     */
    // protected ?string $heading = 'CCTV Overview';
    protected static bool $isLazy = false;

    protected static ?string $pollingInterval = '10s';

    protected static ?int $sort = 2;


    // public static string? 
    protected function getStats(): array
    {
        $today = Carbon::today();

        $report = DailyCctvReport::whereDate('report_date', $today)->latest()->first();
        return [
            Stat::make('Total CCTV FT Tegal', ($report?->cctv_count ?? 0) . ' CCTV')
                ->description("Tanggal: {$today->format('d-m-Y')}")
                ->icon('heroicon-o-video-camera'),
        
            Stat::make('CCTV Aktif Hari Ini', ($report?->active_cctv_count ?? 0) . ' CCTV')
                ->icon('heroicon-o-check-circle')
                ->description("Tanggal: {$today->format('d-m-Y')}")
                ->color('success'),
        
            Stat::make('CCTV Tidak Aktif Hari Ini', ($report?->inactive_cctv_count ?? 0) . ' CCTV')
                ->icon('heroicon-o-x-circle')
                ->description("Tanggal: {$today->format('d-m-Y')}")
                ->color('danger'),
        
            Stat::make('Catatan Hari Ini', '')
                ->icon('heroicon-o-document-text')
                ->description($report?->report_details ?? 'Tidak ada catatan.')
                ->color('gray'),
        ];        
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\MpsWorkingList;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MpsWorkingListStat extends BaseWidget
{
    use HasWidgetShield;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Program Kerja MPS';

    protected static ?string $pollingInterval = '10s';
    
    protected function getStats(): array
    {

        return [
            Stat::make('Total Program Kerja', MpsWorkingList::count())
                ->description('Semua program terdaftar')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary'),
    
            Stat::make('Pending', MpsWorkingList::where('status', 'pending')->count())
                ->description('Belum dimulai')
                ->icon('heroicon-o-clock')
                ->color('danger'),
    
            Stat::make('In Progress', MpsWorkingList::where('status', 'in_progress')->count())
                ->description('Sedang berjalan')
                ->icon('heroicon-o-arrow-path')
                ->color('warning'),
    
            Stat::make('Completed', MpsWorkingList::where('status', 'completed')->count())
                ->description('Sudah selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}

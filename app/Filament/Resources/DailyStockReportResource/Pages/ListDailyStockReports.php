<?php

namespace App\Filament\Resources\DailyStockReportResource\Pages;

use App\Filament\Resources\DailyStockReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyStockReports extends ListRecords
{
    protected static string $resource = DailyStockReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

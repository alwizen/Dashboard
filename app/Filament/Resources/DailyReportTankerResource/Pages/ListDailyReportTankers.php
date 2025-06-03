<?php

namespace App\Filament\Resources\DailyReportTankerResource\Pages;

use App\Filament\Resources\DailyReportTankerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyReportTankers extends ListRecords
{
    protected static string $resource = DailyReportTankerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

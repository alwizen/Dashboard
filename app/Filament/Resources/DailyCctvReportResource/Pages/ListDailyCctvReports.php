<?php

namespace App\Filament\Resources\DailyCctvReportResource\Pages;

use App\Filament\Resources\DailyCctvReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailyCctvReports extends ListRecords
{
    protected static string $resource = DailyCctvReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

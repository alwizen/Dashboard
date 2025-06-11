<?php

namespace App\Filament\Resources\DailyStockReportResource\Pages;

use App\Filament\Resources\DailyStockReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyStockReport extends CreateRecord
{
    protected static string $resource = DailyStockReportResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

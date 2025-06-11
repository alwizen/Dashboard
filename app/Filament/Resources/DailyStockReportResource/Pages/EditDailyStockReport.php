<?php

namespace App\Filament\Resources\DailyStockReportResource\Pages;

use App\Filament\Resources\DailyStockReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyStockReport extends EditRecord
{
    protected static string $resource = DailyStockReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

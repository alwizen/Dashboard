<?php

namespace App\Filament\Resources\DailyCctvReportResource\Pages;

use App\Filament\Resources\DailyCctvReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyCctvReport extends EditRecord
{
    protected static string $resource = DailyCctvReportResource::class;

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

<?php

namespace App\Filament\Resources\DailyCctvReportResource\Pages;

use App\Filament\Resources\DailyCctvReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyCctvReport extends CreateRecord
{
    protected static string $resource = DailyCctvReportResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\DailyReportTankerResource\Pages;

use App\Filament\Resources\DailyReportTankerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyReportTanker extends CreateRecord
{
    protected static string $resource = DailyReportTankerResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

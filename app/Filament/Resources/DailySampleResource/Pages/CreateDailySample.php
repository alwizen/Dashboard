<?php

namespace App\Filament\Resources\DailySampleResource\Pages;

use App\Filament\Resources\DailySampleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDailySample extends CreateRecord
{
    protected static string $resource = DailySampleResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

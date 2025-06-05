<?php

namespace App\Filament\Resources\DailySampleResource\Pages;

use App\Filament\Resources\DailySampleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailySample extends EditRecord
{
    protected static string $resource = DailySampleResource::class;

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

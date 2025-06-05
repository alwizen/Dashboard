<?php

namespace App\Filament\Resources\DailySampleResource\Pages;

use App\Filament\Resources\DailySampleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailySamples extends ListRecords
{
    protected static string $resource = DailySampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\TankerInspectionResource\Pages;

use App\Filament\Resources\TankerInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTankerInspections extends ListRecords
{
    protected static string $resource = TankerInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

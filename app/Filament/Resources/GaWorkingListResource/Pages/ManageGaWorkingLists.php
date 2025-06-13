<?php

namespace App\Filament\Resources\GaWorkingListResource\Pages;

use App\Filament\Resources\GaWorkingListResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGaWorkingLists extends ManageRecords
{
    protected static string $resource = GaWorkingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

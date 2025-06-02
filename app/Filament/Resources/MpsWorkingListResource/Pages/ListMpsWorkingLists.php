<?php

namespace App\Filament\Resources\MpsWorkingListResource\Pages;

use App\Filament\Resources\MpsWorkingListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMpsWorkingLists extends ListRecords
{
    protected static string $resource = MpsWorkingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

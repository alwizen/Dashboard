<?php

namespace App\Filament\Resources\SpendingResource\Pages;

use App\Filament\Resources\SpendingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSpendings extends ManageRecords
{
    protected static string $resource = SpendingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

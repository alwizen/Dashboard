<?php

namespace App\Filament\Resources\FootValveInspactionResource\Pages;

use App\Filament\Resources\FootValveInspactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFootValveInspactions extends ManageRecords
{
    protected static string $resource = FootValveInspactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\TransportirResource\Pages;

use App\Filament\Resources\TransportirResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTransportirs extends ManageRecords
{
    protected static string $resource = TransportirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

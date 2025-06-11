<?php

namespace App\Filament\Resources\BillFleetResource\Pages;

use App\Filament\Resources\BillFleetResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBillFleets extends ManageRecords
{
    protected static string $resource = BillFleetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

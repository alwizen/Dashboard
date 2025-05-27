<?php

namespace App\Filament\Resources\DriverAmtResource\Pages;

use App\Filament\Imports\DriverAmtImporter;
use App\Filament\Resources\DriverAmtResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDriverAmts extends ManageRecords
{
    protected static string $resource = DriverAmtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make('import')
                ->label('Import Driver Amounts')
                // ->icon('heroicon-o-upload')
                ->importer(DriverAmtImporter::class),
        ];}
}

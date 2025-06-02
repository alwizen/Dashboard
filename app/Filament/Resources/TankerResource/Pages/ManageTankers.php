<?php

namespace App\Filament\Resources\TankerResource\Pages;

use App\Filament\Resources\TankerResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\ExportAction;

class ManageTankers extends ManageRecords
{
    protected static string $resource = TankerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(\App\Filament\Exports\TankerExporter::class)
                ->label('Export Tankers')
                ->icon('heroicon-o-cloud-arrow-up'),
            ImportAction::make()
                ->importer(\App\Filament\Imports\TankerImporter::class)
                ->label('Import Tankers')
                ->icon('heroicon-o-cloud-arrow-down'),
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CctvResource\Pages;

use App\Filament\Imports\CctvImporter;
use App\Filament\Resources\CctvResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCctvs extends ManageRecords
{
    protected static string $resource = CctvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
            ->importer(CctvImporter::class)
        ];
    }
}

<?php

namespace App\Filament\Resources\ProgramHistoryResource\Pages;

use App\Filament\Resources\ProgramHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProgramHistories extends ListRecords
{
    protected static string $resource = ProgramHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

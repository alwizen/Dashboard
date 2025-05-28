<?php

namespace App\Filament\Resources\CriteriaTemplateResource\Pages;

use App\Filament\Resources\CriteriaTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCriteriaTemplates extends ListRecords
{
    protected static string $resource = CriteriaTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

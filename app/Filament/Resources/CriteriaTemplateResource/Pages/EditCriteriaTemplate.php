<?php

namespace App\Filament\Resources\CriteriaTemplateResource\Pages;

use App\Filament\Resources\CriteriaTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCriteriaTemplate extends EditRecord
{
    protected static string $resource = CriteriaTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

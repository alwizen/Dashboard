<?php

namespace App\Filament\Resources\CriteriaTemplateResource\Pages;

use App\Filament\Resources\CriteriaTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCriteriaTemplate extends CreateRecord
{
    protected static string $resource = CriteriaTemplateResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

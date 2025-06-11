<?php

namespace App\Filament\Resources\TankerInspectionResource\Pages;

use App\Filament\Resources\TankerInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTankerInspection extends CreateRecord
{
    protected static string $resource = TankerInspectionResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

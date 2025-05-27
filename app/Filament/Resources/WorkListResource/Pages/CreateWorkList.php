<?php

namespace App\Filament\Resources\WorkListResource\Pages;

use App\Filament\Resources\WorkListResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkList extends CreateRecord
{
    protected static string $resource = WorkListResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

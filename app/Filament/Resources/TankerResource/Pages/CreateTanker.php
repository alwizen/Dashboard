<?php

namespace App\Filament\Resources\TankerResource\Pages;

use App\Filament\Resources\TankerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTanker extends CreateRecord
{
    protected static string $resource = TankerResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

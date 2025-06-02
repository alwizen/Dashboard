<?php

namespace App\Filament\Resources\MpsWorkingListResource\Pages;

use App\Filament\Resources\MpsWorkingListResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMpsWorkingList extends CreateRecord
{
    protected static string $resource = MpsWorkingListResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

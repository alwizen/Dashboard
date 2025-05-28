<?php

namespace App\Filament\Resources\ProgramHistoryResource\Pages;

use App\Filament\Resources\ProgramHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProgramHistory extends CreateRecord
{
    protected static string $resource = ProgramHistoryResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

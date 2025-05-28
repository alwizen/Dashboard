<?php

namespace App\Filament\Resources\ProgramHistoryResource\Pages;

use App\Filament\Resources\ProgramHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgramHistory extends EditRecord
{
    protected static string $resource = ProgramHistoryResource::class;

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

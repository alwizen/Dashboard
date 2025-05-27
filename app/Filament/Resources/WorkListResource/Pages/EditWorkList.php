<?php

namespace App\Filament\Resources\WorkListResource\Pages;

use App\Filament\Resources\WorkListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkList extends EditRecord
{
    protected static string $resource = WorkListResource::class;

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

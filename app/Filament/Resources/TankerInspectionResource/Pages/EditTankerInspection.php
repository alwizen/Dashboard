<?php

namespace App\Filament\Resources\TankerInspectionResource\Pages;

use App\Filament\Resources\TankerInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTankerInspection extends EditRecord
{
    protected static string $resource = TankerInspectionResource::class;

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

<?php

namespace App\Filament\Resources\TankerResource\Pages;

use App\Filament\Resources\TankerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTanker extends EditRecord
{
    protected static string $resource = TankerResource::class;

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

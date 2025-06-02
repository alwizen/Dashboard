<?php

namespace App\Filament\Resources\MpsWorkingListResource\Pages;

use App\Filament\Resources\MpsWorkingListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMpsWorkingList extends EditRecord
{
    protected static string $resource = MpsWorkingListResource::class;

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

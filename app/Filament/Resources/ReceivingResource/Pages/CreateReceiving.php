<?php

namespace App\Filament\Resources\ReceivingResource\Pages;

use App\Filament\Resources\ReceivingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReceiving extends CreateRecord
{
    protected static string $resource = ReceivingResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

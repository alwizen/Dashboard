<?php

namespace App\Filament\Resources\MpsWorkingListCategoryResource\Pages;

use App\Filament\Resources\MpsWorkingListCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMpsWorkingListCategories extends ManageRecords
{
    protected static string $resource = MpsWorkingListCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

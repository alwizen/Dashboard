<?php

namespace App\Filament\Resources\AttendanceAmtResource\Pages;

use App\Filament\Resources\AttendanceAmtResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAttendanceAmts extends ManageRecords
{
    protected static string $resource = AttendanceAmtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

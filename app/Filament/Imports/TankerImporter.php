<?php

namespace App\Filament\Imports;

use App\Models\Tanker;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TankerImporter extends Importer
{
    protected static ?string $model = Tanker::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nopol')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('product')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('capacity')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kir_expiry')
                ->rules(['date']),
            ImportColumn::make('kim_expiry')
                ->rules(['date']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('note')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Tanker
    {
        // return Tanker::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Tanker();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your tanker import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

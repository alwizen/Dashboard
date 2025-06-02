<?php

namespace App\Filament\Exports;

use App\Models\Tanker;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TankerExporter extends Exporter
{
    protected static ?string $model = Tanker::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('nopol'),
            ExportColumn::make('product'),
            ExportColumn::make('capacity'),
            ExportColumn::make('kir_expiry'),
            ExportColumn::make('kim_expiry'),
            ExportColumn::make('status'),
            ExportColumn::make('note'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your tanker export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}

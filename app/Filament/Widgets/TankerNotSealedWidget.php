<?php

namespace App\Filament\Widgets;

use App\Models\TankerInspection;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TankerNotSealedWidget extends BaseWidget
{
    protected static ?string $heading = '🚩 MT Tidak Lanjut Alpukat (Tidak Kedap)';

    // protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TankerInspection::query()
                    ->tidakKedap()
                    ->with('tanker')
                    ->latest('inspection_date')
            )
            ->paginated(false)
            ->striped()
            ->poll('30s')
            ->columns([
                Tables\Columns\TextColumn::make('inspection_date')
                    ->label('Tgl. Pengujian')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('tanker.nopol')->label('Nopol'),
                Tables\Columns\TextColumn::make('tanker.comp')->label('Komp'),
                Tables\Columns\TextColumn::make('tanker.transportir.name')->label('Transportir'),
                Tables\Columns\TextColumn::make('tanker.merk')->label('Merk'),
                Tables\Columns\TextColumn::make('tanker.capacity')->label('Kapasitas')->suffix(' KL'), 
            ]);
    }
}

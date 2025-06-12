<?php

namespace App\Filament\Widgets;

use App\Models\BillFleet;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BillFleetTableWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Tagihan Fleet';

    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(BillFleet::query())
            ->poll(5)
            ->columns([
                Tables\Columns\TextColumn::make('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('month')
                    ->label('Bulan'),
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun'),
                Tables\Columns\TextColumn::make('bill_name')
                    ->label('Nama Tagihan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('progress')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BA', 'PR', 'PO', 'SA' => 'primary',
                        'PA' => 'success',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('bill_value')
                    ->label('Jumlah Tagihan')
                    ->summarize(Sum::make())
                    ->numeric(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('month')
                    ->label('Filter Bulan')
                    ->options([
                        'januari' => 'Januari',
                        'februari' => 'Februari',
                        'maret' => 'Maret',
                        'april' => 'April',
                        'mei' => 'Mei',
                        'juni' => 'Juni',
                        'juli' => 'Juli',
                        'agustus' => 'Agustus',
                        'september' => 'September',
                        'oktober' => 'Oktober',
                        'november' => 'November',
                        'desember' => 'Desember',
                    ])
                    ->placeholder('Semua Bulan'),

                Tables\Filters\SelectFilter::make('year')
                    ->label('Filter Tahun')
                    ->options(
                        collect(range(2020, now()->year))
                            ->reverse()
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray()
                    )
                    ->placeholder('Semua Tahun'),
            ]);
    }
}

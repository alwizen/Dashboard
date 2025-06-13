<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\GaWorkingListResource\RelationManagers\ProgressHistoriesRelationManager;
use App\Models\GaWorkingList;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Guava\FilamentModalRelationManagers\Actions\Table\RelationManagerAction;
use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;

class GaWorkingListTableWidget extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Program Kerja GA';

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 7;


    public function table(Table $table): Table
    {
        return $table
            ->query(
                GaWorkingList::query()
            )
            ->poll('5s')
            ->columns([
                Tables\Columns\TextColumn::make('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Program Kerja GA')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
               
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        'pending' => 'danger',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'on_hold' => 'secondary',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->label('Perkiraan Selesai')
                    ->sortable(),
                ProgressBar::make('progress_bar')
                    ->label('Realisasi')
                    ->getStateUsing(fn($record) => [
                        'total' => 100,
                        'progress' => $record->progres,
                    ]),
            ])
            ->actions([
                RelationManagerAction::make('mpsHistory')
                    ->label('')
                    ->relationManager(ProgressHistoriesRelationManager::class)
                    ->icon('heroicon-o-bars-3-bottom-left')
                    ->color('warning')
                    ->tooltip('Riwayat Pekerjaan'),
            ]);
    }
}

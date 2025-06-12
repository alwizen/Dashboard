<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\MpsWorkingListResource\RelationManagers\ProgressHistoriesRelationManager;
use App\Models\MpsWorkingList;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Guava\FilamentModalRelationManagers\Actions\Table\RelationManagerAction;
use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;
use Illuminate\Database\Eloquent\Model;

class MpsWorkingListWidget extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = '';

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 0;


    public function table(Table $table): Table
    {
        return $table
            ->query(
                MpsWorkingList::query()
            )
            ->poll('5s')
            ->columns([
                Tables\Columns\TextColumn::make('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Program Kerja MPS')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('mpsCategory.name')
                    ->numeric()
                    ->label('Kategori Program')
                    ->sortable(),
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

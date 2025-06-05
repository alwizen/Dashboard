<?php

namespace App\Filament\Widgets;

use App\Models\MpsWorkingList;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;
use Illuminate\Database\Eloquent\Model;

class MpsWorkingListWidget extends BaseWidget
{
    protected static ?string $heading = '';

//    protected static ?string $pollingInterval = '5s';

   protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;


    public function table(Table $table): Table
    {
        return $table
            ->query(
                MpsWorkingList::query()
            )
            ->paginated(false)
            ->striped()
            ->poll('5s')
            ->columns([
                Tables\Columns\TextColumn::make('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Program Kerja MPS')
//                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                ProgressBar::make('progress_bar')
                    ->label('Realisasi')
                    ->getStateUsing(fn($record) => [
                        'total' => 100,
                        'progress' => $record->progres,
                    ]),
            ]);
    }
}

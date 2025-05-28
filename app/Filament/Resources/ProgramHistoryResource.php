<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramHistoryResource\Pages;
use App\Filament\Resources\ProgramHistoryResource\RelationManagers;
use App\Models\ProgramHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramHistoryResource extends Resource
{
    protected static ?string $model = ProgramHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Program History';

    protected static ?string $modelLabel = 'Program History';

    protected static ?string $pluralModelLabel = 'Program Histories';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'New ✨';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('program.title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('program.division.name')
                    ->label('division')
                    ->badge(),
                Tables\Columns\TextColumn::make('field_changed')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('old_value')
                    ->limit(20)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('new_value')
                    ->limit(20)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Changed By')
                    ->sortable()
                    ->placeholder('System'),
                Tables\Columns\TextColumn::make('changed_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('program.division_id')
                    ->relationship('program.division', 'name')
                    ->preload()
                    ->label('division'),
                Tables\Filters\SelectFilter::make('field_changed')
                    ->options([
                        'status' => 'Status',
                        'overall_progress' => 'Progress',
                        'due_date' => 'Due Date',
                        'notes' => 'Notes',
                    ])
                    ->label('Field Changed'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('changed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProgramHistories::route('/'),
//            'view' => Pages\ViewProgramHistory::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

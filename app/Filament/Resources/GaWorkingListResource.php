<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaWorkingListResource\Pages;
use App\Filament\Resources\GaWorkingListResource\RelationManagers\ProgressHistoriesRelationManager;
use App\Models\GaWorkingList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\Table\RelationManagerAction;
use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GaWorkingListResource extends Resource
{
    protected static ?string $model = GaWorkingList::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Program Kerja GA';

    protected static ?string $label = 'Program Kerja GA';

    protected static ?string $navigationGroup = 'GA';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('progres')
                    ->options([
                        '0' => '0',
                        '10' => '10',
                        '20' => '20',
                        '30' => '30',
                        '40' => '40',
                        '50' => '50',
                        '60' => '60',
                        '70' => '70',
                        '80' => '80',
                        '90' => '90',
                        '100' => '100',
                    ])
                    ->suffix(' %'),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('due_date'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'on_hold' => 'On Hold',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Program Kerja'),
                // Tables\Columns\TextColumn::make('progres')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->label('tgl. Mulai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->label('tgl. Selesai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        'pending' => 'danger',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'on_hold' => 'secondary',
                        default => 'danger',
                    }),
                ProgressBar::make('progress_bar')
                    ->getStateUsing(fn($record) => [
                        'total' => 100,
                        'progress' => $record->progres,
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                RelationManagerAction::make('mpsHistory')
                    ->label('')
                    ->relationManager(ProgressHistoriesRelationManager::make())
                    ->icon('heroicon-o-paper-clip')
                    ->color('warning')
                    ->tooltip('Riwayat Pekerjaan'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGaWorkingLists::route('/'),
        ];
    }
}

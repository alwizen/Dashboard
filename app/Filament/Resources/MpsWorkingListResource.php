<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MpsWorkingListResource\Pages;
use App\Filament\Resources\MpsWorkingListResource\RelationManagers;
use App\Filament\Resources\MpsWorkingListResource\RelationManagers\ProgressHistoriesRelationManager;
use App\Models\MpsProgressHistory;
use App\Models\MpsWorkingList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\Table\RelationManagerAction;
use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MpsWorkingListResource extends Resource
{
    protected static ?string $model = MpsWorkingList::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Progam Kerja MPS';

    protected static ?string $label = 'Progam Kerja MPS';

    protected static ?string $navigationGroup = 'MPS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('progres')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->helperText(
                        fn(string $operation): ?string =>
                        $operation === 'edit' ? 'Progress diupdate melalui History. Tambah history baru untuk mengubah progress.' : null
                    ),
                Forms\Components\MarkdownEditor::make('description')
                    ->columnSpanFull()
                    ->label('Description Progress')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->relationship('mpsCategory', 'name')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'on_hold' => 'On Hold',
                    ])
                    ->default('in_progress')
                    ->required(),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('due_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Program Kerja')
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('progres')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('mpsCategory.name')
                    ->numeric()
                    ->label('Kategori Program')
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
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->label('Tanggal Mulai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->label('Batas Waktu')
                    ->sortable(),
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

                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make()
                ])
                    ->tooltip('Aksi')
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
            'index' => Pages\ListMpsWorkingLists::route('/'),
            'create' => Pages\CreateMpsWorkingList::route('/create'),
            'edit' => Pages\EditMpsWorkingList::route('/{record}/edit'),
        ];
    }
}

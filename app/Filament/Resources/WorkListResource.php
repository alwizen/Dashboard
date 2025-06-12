<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkListResource\Pages;
use App\Filament\Resources\WorkListResource\RelationManagers;
use App\Filament\Resources\WorkListResource\RelationManagers\HistoriesRelationManager;
use App\Models\WorkList;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\Table\RelationManagerAction;
use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkListResource extends Resource
{
    protected static ?string $model = WorkList::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    // protected static ?string $navigationGroup = 'Project Management';
    protected static bool $shouldRegisterNavigation = false;

    
    protected static ?string $navigationLabel = 'Program Kerja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Program Kerja')
                    ->description('Isi detail program kerja yang akan dikelola.')
                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'task' => 'Task',
                                'project' => 'Project',
                                'reguler' => 'Reguler',
                                'feature' => 'Feature',
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Department')
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->label('Departemen')
                            ->relationship('department', 'name')
                            ->required(),

                        Forms\Components\TextInput::make('progress')
                            ->label('Progres (%)')
                            ->numeric()
                            ->suffix('%')
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100)
                            ->required()
                            // Disable progress field saat edit (karena akan diupdate via relation manager)
                            ->disabled(fn(string $operation): bool => $operation === 'edit')
                            ->dehydrated() // Pastikan value tetap disimpan meski disabled
                            ->helperText(
                                fn(string $operation): ?string =>
                                $operation === 'edit' ? 'Progress diupdate melalui History. Tambah history baru untuk mengubah progress.' : null
                            ),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])
                            ->disabled() // atau ->readOnly()
                            ->default('pending'),

                        Forms\Components\DatePicker::make('due_date')
                            ->label('Batas Waktu'),
                        Forms\Components\RichEditor::make('description')
                            ->label('Catatan')
                            ->disableToolbarButtons([
                                'attachFiles',
                            ])
                            ->columnSpanFull(),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                //                ->description(fn (WorkList $record): string => $record->description),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        'pending' => 'danger',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        default => 'danger',
                    }),

                // Masih tetap tampilkan kolom angka progres untuk filter/sort
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),

                // Tambahan: progress dalam bentuk bar
                ProgressBar::make('progress_bar')
                    ->getStateUsing(fn($record) => [
                        'total' => 100,
                        'progress' => $record->progress,
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
                RelationManagerAction::make('histories')
                    ->label('Riwayat Pekerjaan')
                    ->relationManager(HistoriesRelationManager::make())
                    ->icon('heroicon-o-paper-clip')
                    ->color('gray')
                    ->tooltip('Lihat riwayat progress'),
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\HistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkLists::route('/'),
            'create' => Pages\CreateWorkList::route('/create'),
            'edit' => Pages\EditWorkList::route('/{record}/edit'),
        ];
    }
}

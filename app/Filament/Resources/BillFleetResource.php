<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillFleetResource\Pages;
use App\Filament\Resources\BillFleetResource\RelationManagers;
use App\Models\BillFleet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillFleetResource extends Resource
{
    protected static ?string $model = BillFleet::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Fleet Management';

    protected static ?string $label = 'Tagihan Fleet';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('month')
                    ->required()
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
                    ->label('Bulan'),
                Forms\Components\TextInput::make('tahun')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->default(date('Y'))
                    ->label('Tahun'),
                Forms\Components\TextInput::make('bill_name')
                ->label('Nama Tagihan'),
                Forms\Components\TextInput::make('bill_value')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Nilai Tagihan'),

                Forms\Components\Select::make('progress')
                    ->options([
                        'BA' => 'BA',
                        'PR' => 'PR',
                        'PO' => 'PO',
                        'SA' => 'SA',
                        'PA' => 'PA',
                    ])
                    ->default('BA')
                    ->label('Progres'),

                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'berjalan' => 'Berjalan',
                        'done' => 'Done (PA)',
                    ])
                    ->default('berjalan')
                    ->label('Status'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('month')
                    ->label('Bulan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bill_name')
                    ->label('Nama Tagihan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('progress')
                    ->badge()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'BA' => 'primary',
                        'PR' => 'primary',
                        'PO' => 'primary',
                        'SA' => 'primary',
                        'PA' => 'success',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    // ->color(fn(string $state): string => match ($state) {
                    //     'draft' => 'gray',
                    //     'progress' => 'warning',
                    //     'done' => 'success',
                    // })
                    ->searchable(),

                Tables\Columns\TextColumn::make('bill_value')
                    ->label('Jumlah Tagihan')
                    ->summarize(Sum::make())
                    ->numeric()
                    ->sortable(),

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
                            ->mapWithKeys(fn ($year) => [$year => $year])
                            ->toArray()
                    )
                    ->placeholder('Semua Tahun'),
            ])
            ->actions([
                Action::make('toPR')
                    ->label('Tandai PR')
                    ->icon('heroicon-o-information-circle') //check-badge
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->progress === 'BA')
                    ->action(function ($record) {
                        $record->progress = 'PR';
                        $record->save();

                        Notification::make()
                            ->title('progress diubah ke PR')
                            ->success()
                            ->send();
                    }),

                Action::make('toPO')
                    ->label('Tandai PO')
                    ->color('warning')
                    ->icon('heroicon-o-information-circle') //check-badge
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->progress === 'PR')
                    ->action(function ($record) {
                        $record->progress = 'PO';
                        $record->save();

                        Notification::make()
                            ->title('progress diubah ke PO')
                            ->success()
                            ->send();
                    }),

                Action::make('toSA')
                    ->label('Tandai SA')
                    ->icon('heroicon-o-information-circle') //check-badge
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->progress === 'PO')
                    ->action(function ($record) {
                        $record->progress = 'SA';
                        $record->save();

                        Notification::make()
                            ->title('progress diubah ke SA')
                            ->success()
                            ->send();
                    }),

                Action::make('toPA')
                    ->label('Tandai PA')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn($record) => $record->progress === 'SA')
                    ->action(function ($record) {
                        $record->progress = 'PA';
                        $record->save();

                        Notification::make()
                            ->title('progress diubah ke PA')
                            ->success()
                            ->send();
                    }),

                ActionGroup::make([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBillFleets::route('/'),
        ];
    }
}

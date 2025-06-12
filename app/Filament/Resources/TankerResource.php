<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TankerResource\Pages;
use App\Filament\Resources\TankerResource\RelationManagers;
use App\Filament\Resources\TankerResource\RelationManagers\KimHistoriesRelationManager;
use App\Filament\Resources\TankerResource\RelationManagers\KirHistoriesRelationManager;
use App\Filament\Resources\TankerResource\RelationManagers\MaintenancesRelationManager;
use App\Models\Tanker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\Table\RelationManagerAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TankerResource extends Resource
{
    protected static ?string $model = Tanker::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Fleet Management';

    protected static ?string $label = 'Mobil Tangki';

    protected static ?int $navigationSort = 2;

    public static function getGloballySearchableAttributes(): array
    {
        return ['nopol', 'capacity', 'status', 'note'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'nopol' => $record->nopol,
            'capacity' => $record->capacity,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('nopol')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('product')
                            ->required()
                            ->default('multi')
                            ->maxLength(255),
                        Forms\Components\Select::make('transportir_id')
                            ->relationship('transportir', 'name'),
                        Forms\Components\TextInput::make('merk'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Spesifikasi Tangki')
                    ->schema([
                        Forms\Components\Select::make('comp')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                            ]),
                        Forms\Components\Select::make('capacity')
                            ->required()
                            ->suffix(' Kl')
                            ->options([
                                '5' => '5',
                                '8' => '8',
                                '16' => '16',
                                '24' => '24',
                                '32' => '32'
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Dokumen Kendaraan')
                    ->schema([
                        Forms\Components\DatePicker::make('kir_expiry'),
                        Forms\Components\DatePicker::make('kim_expiry'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status dan Catatan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'available' => 'Available',
                                'under_maintenance' => 'Under Maintenance',
                                'afkir' => 'AFKIR',
                            ])
                            ->default('available'),
                        Forms\Components\TextInput::make('note')
                            ->maxLength(255)
                            ->default(null),
                    ])
                    ->columns(2),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('nopol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transportir.name')
                    ->label('Transportir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                ->label('Kap')
                ->suffix(' KL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comp')
                    ->searchable()
                    ->label('Komp'),
                Tables\Columns\TextColumn::make('merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kir_expiry')
                    ->date()
                    ->label('Tanggal KIR')
                    ->sortable()
                    ->color(
                        fn($record) =>
                        $record->kir_expiry && $record->kir_expiry->isBefore(now()->addWeek())
                            ? 'danger'
                            : null
                    ),

                Tables\Columns\TextColumn::make('kim_expiry')
                    ->label('Tanggal KIM')
                    ->date()
                    ->sortable()
                    ->color(
                        fn($record) =>
                        $record->kim_expiry && $record->kim_expiry->isBefore(now()->addWeek())
                            ? 'danger'
                            : null
                    ),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'under_maintenance' => 'warning',
                        'afkir' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('note')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ActionGroup::make([
                    RelationManagerAction::make('historiesMaintenance')
                        ->label('Riwayat Perbaikan')
                        ->relationManager(MaintenancesRelationManager::make())
                        ->icon('heroicon-o-arrow-uturn-right')
                        ->color('primary')
                        ->tooltip('Lihat riwayat Perbaikan'),

                    RelationManagerAction::make('historiesKim')
                        ->label('Riwayat Kim')
                        ->relationManager(KimHistoriesRelationManager::make())
                        ->icon('heroicon-o-arrow-uturn-right')
                        ->color('primary')
                        ->tooltip('Lihat riwayat Kim'),

                    RelationManagerAction::make('historiesKir')
                        ->label('Riwayat Kir')
                        ->relationManager(KirHistoriesRelationManager::make())
                        ->icon('heroicon-o-arrow-uturn-right')
                        ->color('primary')
                        ->tooltip('Lihat riwayat Kir'),
                ])
                    ->icon('heroicon-m-clock')
                    ->tooltip('Riwayat')
                    ->label(''),

                ActionGroup::make([
                    Tables\Actions\Action::make('set_maintenance')
                        ->label('Set Under Maintenance')
                        ->icon('heroicon-m-wrench-screwdriver')
                        ->color('warning')
                        ->visible(fn(Tanker $record) => $record->status !== 'under_maintenance')
                        ->requiresConfirmation()
                        ->modalHeading('Set Status Under Maintenance')
                        ->modalDescription(fn(Tanker $record) => "Apakah Anda yakin ingin mengubah status tanker {$record->nopol} menjadi Under Maintenance?")
                        ->action(function (Tanker $record) {
                            $record->update(['status' => 'under_maintenance']);

                            Notification::make()
                                ->title('Status tanker berhasil diubah')
                                ->body("Tanker {$record->nopol} telah diset menjadi Under Maintenance")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('set_afkir')
                        ->label('Set Afkir')
                        ->icon('heroicon-m-archive-box-x-mark')
                        ->color('danger')
                        ->visible(fn(Tanker $record) => $record->status !== 'afkir')
                        ->requiresConfirmation()
                        ->modalHeading('Set Status Afkir')
                        ->modalDescription(fn(Tanker $record) => "Yakin ubah status tanker {$record->nopol} menjadi AFKIR?")
                        ->action(function (Tanker $record) {
                            $record->update(['status' => 'afkir']);

                            Notification::make()
                                ->title('Status diubah')
                                ->body("Tanker {$record->nopol} diset ke AFKIR")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('set_available')
                        ->label('Set Available')
                        ->icon('heroicon-m-check-badge')
                        ->color('success')
                        ->visible(fn(Tanker $record) => $record->status === 'under_maintenance')
                        ->requiresConfirmation()
                        ->modalHeading('Set Status Available')
                        ->modalDescription(fn(Tanker $record) => "Apakah Anda yakin ingin mengubah status tanker {$record->nopol} menjadi Available?")
                        ->action(function (Tanker $record) {
                            $record->update(['status' => 'available']);

                            Notification::make()
                                ->title('Status tanker berhasil diubah')
                                ->body("Tanker {$record->nopol} telah diset menjadi Available")
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->tooltip('Action')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // TankerMaintenanceRelationManager::class,
            // TankerKirHistoryRelationManager::class,
            // TankerKimHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTankers::route('/'),
            'create' => Pages\CreateTanker::route('/create'),
            'edit' => Pages\EditTanker::route('/{record}/edit'),
        ];
    }
}

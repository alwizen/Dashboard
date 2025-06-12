<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FootValveInspactionResource\Pages;
use App\Filament\Resources\FootValveInspactionResource\RelationManagers;
use App\Models\FootValveInspaction;
use App\Models\Tanker;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class FootValveInspactionResource extends Resource
{
    protected static ?string $model = FootValveInspaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?string $navigationGroup = 'Fleet Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('date')
                ->default(now())
                ->label('Tanggal Inspeksi')
                ->required(),

            Select::make('tanker_id')
                ->label('Nopol')
                ->options(Tanker::pluck('nopol', 'id'))
                ->searchable()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        $tanker = Tanker::with('transportir')->find($state);
                        if ($tanker) {
                            $set('merk', $tanker->merk);
                            $set('transportir', $tanker->transportir?->name ?? '-');
                        }
                    } else {
                        // Reset fields when no tanker selected
                        $set('merk', '');
                        $set('transportir', '');
                    }
                }),

            TextInput::make('merk')
                ->label('Merk')
                ->disabled()
                ->reactive()
                ->dehydrated(false), // Tidak ikut disimpan

            TextInput::make('transportir')
                ->label('Transportir')
                ->disabled()
                ->reactive()
                ->dehydrated(false),

            FileUpload::make('photo1')
                ->label('Photo 1')
                ->directory('foot-valve-inspections')
                ->image()
                ->maxSize(1024),

            FileUpload::make('photo2')
                ->label('Photo 2')
                ->directory('foot-valve-inspections')
                ->image()
                ->maxSize(1024),

            FileUpload::make('photo3')
                ->label('Photo 3')
                ->directory('foot-valve-inspections')
                ->image()
                ->maxSize(1024),

            TextInput::make('note')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanker.nopol')
                    ->label('Nopol')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanker.merk')
                    ->label('Merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanker.transportir.name')
                    ->label('Transportir')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo1')
                    ->label('Photo 1')
                    ->circular()
                    ->size(40),
                Tables\Columns\ImageColumn::make('photo2')
                    ->label('Photo 2')
                    ->circular()
                    ->size(40),
                Tables\Columns\ImageColumn::make('photo3')
                    ->label('Photo 3')
                    ->circular()
                    ->size(40),
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
                Tables\Filters\SelectFilter::make('tanker_id')
                    ->label('Nopol')
                    ->options(Tanker::pluck('nopol', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_photos')
                    ->label('Lihat Foto')
                    ->icon('heroicon-o-photo')
                    ->modalHeading('Foto Inspeksi')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->action(fn() => null) // Tidak ada aksi karena hanya tampilan
                    ->modalContent(fn($record) => view('filament.modals.view-footvalve-photos', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFootValveInspactions::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TankerInspectionResource\Pages;
use App\Models\TankerInspection;
use App\Models\Tanker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;

class TankerInspectionResource extends Resource
{
    protected static ?string $model = TankerInspection::class;

    protected static ?string $navigationGroup = 'Fleet Management';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationLabel = 'Pengecekan Alpukat';
    
    protected static ?string $modelLabel = 'Pengecekan Alpukat';
    
    protected static ?string $pluralModelLabel = 'Pengecekan Alpukat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengecekan')
                    ->schema([
                        Forms\Components\Select::make('tanker_id')
                            ->label('Mobil Tangki')
                            ->options(Tanker::all()->pluck('nopol', 'id'))
                            ->required()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                // Reset semua comp status saat tanker berubah
                                $set('comp_1_status', null);
                                $set('comp_2_status', null);
                                $set('comp_3_status', null);
                                $set('comp_4_status', null);
                                $set('comp_5_status', null);
                            }),
                        
                        Forms\Components\DatePicker::make('inspection_date')
                            ->label('Tanggal Pengecekan')
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status Kompartemen')
                    ->schema([
                        // Kompartemen 1
                        Forms\Components\Select::make('comp_1_status')
                            ->label('Kompartemen 1')
                            ->options([
                                'kedap' => 'Kedap',
                                'tidak_kedap' => 'Tidak Kedap',
                            ])
                            ->required()
                            ->visible(fn (Get $get) => self::isCompartmentVisible($get, 1)),

                        // Kompartemen 2
                        Forms\Components\Select::make('comp_2_status')
                            ->label('Kompartemen 2')
                            ->options([
                                'kedap' => 'Kedap',
                                'tidak_kedap' => 'Tidak Kedap',
                            ])
                            ->required()
                            ->visible(fn (Get $get) => self::isCompartmentVisible($get, 2)),

                        // Kompartemen 3
                        Forms\Components\Select::make('comp_3_status')
                            ->label('Kompartemen 3')
                            ->options([
                                'kedap' => 'Kedap',
                                'tidak_kedap' => 'Tidak Kedap',
                            ])
                            ->required()
                            ->visible(fn (Get $get) => self::isCompartmentVisible($get, 3)),

                        // Kompartemen 4
                        Forms\Components\Select::make('comp_4_status')
                            ->label('Kompartemen 4')
                            ->options([
                                'kedap' => 'Kedap',
                                'tidak_kedap' => 'Tidak Kedap',
                            ])
                            ->required()
                            ->visible(fn (Get $get) => self::isCompartmentVisible($get, 4)),

                        // Kompartemen 5
                        Forms\Components\Select::make('comp_5_status')
                            ->label('Kompartemen 5')
                            ->options([
                                'kedap' => 'Kedap',
                                'tidak_kedap' => 'Tidak Kedap',
                            ])
                            ->required()
                            ->visible(fn (Get $get) => self::isCompartmentVisible($get, 5)),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanker.nopol')
                    ->label('Nopol')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('inspection_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tanker.comp')
                    ->label('Jumlah Komp')
                    ->alignCenter(),

                Tables\Columns\BadgeColumn::make('overall_status')
                    ->label('Status Keseluruhan')
                    ->colors([
                        'success' => 'kedap',
                        'danger' => 'tidak_kedap',
                    ])
                    ->formatStateUsing(fn (string $state): string => 
                        $state === 'kedap' ? 'Kedap' : 'Tidak Kedap'
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('overall_status')
                    ->label('Status')
                    ->options([
                        'kedap' => 'Kedap',
                        'tidak_kedap' => 'Tidak Kedap',
                    ]),
                
                Tables\Filters\SelectFilter::make('tanker_id')
                    ->label('Mobil Tangki')
                    ->options(Tanker::all()->pluck('nopol', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('inspection_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTankerInspections::route('/'),
            'create' => Pages\CreateTankerInspection::route('/create'),
            // 'view' => Pages\ViewTankerInspection::route('/{record}'),
            'edit' => Pages\EditTankerInspection::route('/{record}/edit'),
        ];
    }

    // Helper function untuk menentukan apakah kompartemen harus ditampilkan
    private static function isCompartmentVisible(Get $get, int $compNumber): bool
    {
        $tankerId = $get('tanker_id');
        
        if (!$tankerId) {
            return false;
        }

        $tanker = Tanker::find($tankerId);
        
        return $tanker && $compNumber <= $tanker->comp;
    }
}
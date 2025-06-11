<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailySampleResource\Pages;
use App\Filament\Resources\DailySampleResource\RelationManagers;
use App\Models\DailySample;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailySampleResource extends Resource
{
    protected static ?string $model = DailySample::class;

    protected static ?string $navigationIcon = '';

    protected static ?string $navigationGroup = 'Laporan Harian';

    protected static ?string $navigationLabel = 'Harian Retain Sample';

    protected static ?string $label = 'Laporan Harian Retain Sample';    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sample Information')
                    ->schema([
                        Forms\Components\DatePicker::make('sample_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\FileUpload::make('photo')
                            ->label('Sample Photo')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->directory('daily_samples')
                            ->preserveFilenames()
                            ->maxSize(1024),
                    ])
                    ->columns(2),

                Section::make('Sample Items')
                    ->schema([
                        Repeater::make('dailySampleItems')
                            ->relationship()
                            ->columns(5)
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->required(),

                                Forms\Components\TextInput::make('dencity')
                                    ->required()
                                    ->numeric()
                                    ->step(0.001),

                                Forms\Components\TextInput::make('temperature')
                                    ->required()
                                    ->suffix('°C')
                                    ->numeric(),

                                Forms\Components\Toggle::make('nil_water')
                                    ->label('Nil water')
                                    ->reactive()
                                    ->required(),

                                Forms\Components\TextInput::make('water_volume')
                                    ->nullable()
                                    ->numeric()
                                    ->step(0.01)
                                    ->label('Water Volume (L)')
                                    ->visible(fn($get) => $get('nil_water') === true),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sample_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Sample Photo')
                    ->square(),
                Tables\Columns\TextColumn::make('dailySampleItems.product.name')
                    ->label('Product')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('dailySampleItems.dencity')
                    ->label('Dencity')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('dailySampleItems.temperature')
                    ->label('Temperature (°C)')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('dailySampleItems.nil_water')
                    ->label('Nil Water')
                    ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('dailySampleItems.water_volume')
                    ->label('Water Volume (L)')
                    ->listWithLineBreaks()
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailySamples::route('/'),
            'create' => Pages\CreateDailySample::route('/create'),
            'edit' => Pages\EditDailySample::route('/{record}/edit'),
        ];
    }
}

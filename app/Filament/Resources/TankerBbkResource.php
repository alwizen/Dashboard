<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TankerBbkResource\Pages;
use App\Filament\Resources\TankerBbkResource\RelationManagers;
use App\Models\TankerBbk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TankerBbkResource extends Resource
{
    protected static ?string $model = TankerBbk::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-thumb-up';

    protected static ?string $navigationGroup = 'Fleet Management';

    protected static ?string $label = 'Mobil Tangki BBK';

    protected static ?string $navigationLabel = 'Mobil Tangki BBK';

    public static function form(Form $form): Form
    {
        return $form
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
                Forms\Components\Select::make('comp')
                    ->options([
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5'
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
                Forms\Components\DatePicker::make('kir_expiry'),
                Forms\Components\DatePicker::make('kim_expiry'),
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
            ]);
    }

     public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nopol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kir_expiry')
                    ->date()
                    ->sortable()
                    ->color(
                        fn($record) =>
                        $record->kir_expiry && $record->kir_expiry->isBefore(now()->addWeek())
                            ? 'danger'
                            : null
                    ),

                Tables\Columns\TextColumn::make('kim_expiry')
                    ->date()
                    ->sortable()
                    ->color(
                        fn($record) =>
                        $record->kim_expiry && $record->kim_expiry->isBefore(now()->addWeek())
                            ? 'danger'
                            : null
                    ),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageTankerBbks::route('/'),
        ];
    }
}

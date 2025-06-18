<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverAmtResource\Pages;
use App\Filament\Resources\DriverAmtResource\RelationManagers;
use App\Models\DriverAmt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DriverAmtResource extends Resource
{

    protected static ?string $model = DriverAmt::class;

    protected static ?string $navigationLabel = 'Awak Mobil Tangki';

    protected static ?string $label = 'Awak Mobil Tangki';

    protected static ?string $navigationGroup = 'Fleet';

    protected static ?string $navigationIcon = 'heroicon-o-identification';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nip')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->datalist([
                        'AMT 1',
                        'AMT 2'
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('note')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('rfid_code')
                    ->label('RFID')
                    ->required()
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rfid_code')
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

            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDriverAmts::route('/'),
        ];
    }
}

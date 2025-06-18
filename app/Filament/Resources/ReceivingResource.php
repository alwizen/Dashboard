<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceivingResource\Pages;
use App\Filament\Resources\ReceivingResource\RelationManagers;
use App\Models\Receiving;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class ReceivingResource extends Resource
{
    protected static ?string $model = Receiving::class;

    protected static ?string $navigationLabel = 'tes';

    // protected static ?string $navigationGroup = 'Fleet';

    // protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\DatePicker::make('receiving_date')
                    ->default(now())
                    ->required(),
                Repeater::make('items')
                    ->relationship('items')
                    ->columns(2)
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name'),
                        TextInput::make('value')
                            ->suffix('Kl')
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('receiving_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('items.product.name')
                    ->listWithLineBreaks(),
                Tables\Columns\TextColumn::make('items.value')
                    ->listWithLineBreaks()
                    ->suffix(' Kl')
                    ->label('Jumlah'),
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
            'index' => Pages\ListReceivings::route('/'),
            'create' => Pages\CreateReceiving::route('/create'),
            'edit' => Pages\EditReceiving::route('/{record}/edit'),
        ];
    }
}

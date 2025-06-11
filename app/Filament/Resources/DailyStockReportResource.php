<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyStockReportResource\Pages;
use App\Filament\Resources\DailyStockReportResource\RelationManagers;
use App\Models\DailyStockReport;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyStockReportResource extends Resource
{
    protected static ?string $model = DailyStockReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')->required(),
                Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('tank_number')->required(),

                TextInput::make('safe_cap_level')->numeric()->nullable(),
                TextInput::make('safe_cap_volume')->numeric()->nullable(),
                TextInput::make('opening_stock_level')->numeric()->nullable(),
                TextInput::make('opening_stock_volume')->numeric()->nullable(),

                TextInput::make('current_stock_level')->numeric()->nullable(),
                TextInput::make('current_stock_volume')->numeric()->nullable(),
                TextInput::make('current_air_level')->numeric()->nullable(),
                TextInput::make('current_air_volume')->numeric()->nullable(),

                TextInput::make('dead_stock')->numeric()->nullable(),
                TextInput::make('pump_stock')->numeric()->nullable(),
                TextInput::make('ullage')->numeric()->nullable(),

                TextInput::make('ddt')->numeric()->nullable(),
                TextInput::make('cd')->numeric()->nullable(),

                TextInput::make('floating_tegak')->numeric()->nullable(),
                TextInput::make('stafle_moss')->numeric()->nullable(),

                TextInput::make('next_supply')->numeric()->nullable(),
                TextInput::make('receipt')->numeric()->nullable(),
                TextInput::make('actual_throughput')->numeric()->nullable(),

                TextInput::make('working_loss_liter')->numeric()->nullable(),
                TextInput::make('working_loss_percent')->numeric()->nullable(),
                TextInput::make('tank_decrease')->numeric()->nullable(),
            ])
            ->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListDailyStockReports::route('/'),
            'create' => Pages\CreateDailyStockReport::route('/create'),
            'edit' => Pages\EditDailyStockReport::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceAmtResource\Pages;
use App\Filament\Resources\AttendanceAmtResource\RelationManagers;
use App\Models\AttendanceAmt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceAmtResource extends Resource
{
    protected static ?string $model = AttendanceAmt::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static ?string $navigationGroup = 'Fleet';

    protected static ?string $navigationLabel = 'Kehadiran AMT';
    
    protected static ?string $label = 'Kehadiran AMT';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('driver_amt_id')
                    ->relationship('driverAmt', 'name')
                    ->required(),
                Forms\Components\DateTimePicker::make('scanned_at')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('driverAmt.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scanned_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAttendanceAmts::route('/'),
        ];
    }
}

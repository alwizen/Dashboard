<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyReportTankerResource\Pages;
use App\Filament\Resources\DailyReportTankerResource\RelationManagers;
use App\Models\DailyReportTanker;
use App\Models\Tanker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyReportTankerResource extends Resource
{
    protected static ?string $model = DailyReportTanker::class;

    protected static ?string $navigationGroup = 'Laporan Harian';

    protected static ?string $label = 'Laporan Harian Mobil Tangki';

    protected static ?string $pluralLabel = 'Harian Mobil Tangki';

    protected static ?string $navigationIcon = '';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('report_date')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('count_tankers')
                    ->required()
                    ->default(fn() => Tanker::count())
                    ->numeric(),
                Forms\Components\TextInput::make('count_tanker_under_maintenance')
                    ->required()
                    ->default(fn() => Tanker::where('status', 'under_maintenance')->count()) // 'under_maintenance' status
                    ->numeric(),
                Forms\Components\TextInput::make('count_tanker_afkir')
                    ->required()
                    ->default(fn() => Tanker::where('status', 'afkir')->count()) // 'afkir' status
                    ->numeric(),
                Forms\Components\TextInput::make('count_tanker_available')
                    ->default(fn() => Tanker::where('status', 'available')->count()) // 'available' status
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('count_tankers')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('count_tanker_under_maintenance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('count_tanker_afkir')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('count_tanker_available')
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
            'index' => Pages\ListDailyReportTankers::route('/'),
            'create' => Pages\CreateDailyReportTanker::route('/create'),
            'edit' => Pages\EditDailyReportTanker::route('/{record}/edit'),
        ];
    }
}

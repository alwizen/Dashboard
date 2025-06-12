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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

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
                    ->label('Tanggal')
                    ->default(now()),
                Forms\Components\TextInput::make('count_tankers')
                    ->required()
                    ->label('Total MT')
                    ->default(fn() => Tanker::count())
                    ->numeric(),
                Forms\Components\TextInput::make('count_tanker_under_maintenance')
                    ->required()
                    ->label('Perbaikan')
                    ->default(fn() => Tanker::where('status', 'under_maintenance')->count()) // 'under_maintenance' status
                    ->numeric(),
                Forms\Components\TextInput::make('count_tanker_afkir')
                    ->required()
                    ->label('Afkir')
                    ->default(fn() => Tanker::where('status', 'afkir')->count()) // 'afkir' status
                    ->numeric(),
                Forms\Components\TextInput::make('count_tanker_available')
                    ->default(fn() => Tanker::where('status', 'available')->count()) // 'available' status
                    ->required()
                    ->label('Oprational')
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
                    ->sortable()
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('count_tankers')
                    ->numeric()
                    ->label('Jumlah MT')
                    ->sortable()
                    ->suffix(' MT'),
                Tables\Columns\TextColumn::make('count_tanker_under_maintenance')
                    ->label('Perbaikan')
                    ->suffix(' MT')
                    ->badge()
                    ->color('warning')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('count_tanker_afkir')
                    ->numeric()
                    ->label('Afkir')
                    ->badge()
                    ->color('danger')
                    ->sortable()
                    ->suffix(' MT'),
                Tables\Columns\TextColumn::make('count_tanker_available')
                    ->numeric()
                    ->suffix(' MT')
                    ->label('Oprational')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_capacity_available')
                    ->label('Kapasitas (KL)')
                    ->suffix(' KL')
                    ->numeric()
                    ->badge()
                    ->color('primary')
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
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDailyReportTankers::route('/'),
        ];
    }
}

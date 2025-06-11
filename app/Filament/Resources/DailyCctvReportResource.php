<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyCctvReportResource\Pages;
use App\Filament\Resources\DailyCctvReportResource\RelationManagers;
use App\Models\Cctv;
use App\Models\DailyCctvReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyCctvReportResource extends Resource
{
    protected static ?string $model = DailyCctvReport::class;

    protected static ?string $navigationIcon = '';

    protected static ?string $navigationGroup = 'Laporan Harian';

    protected static ?string $label = 'Laporan CCTV Harian';

    protected static ?string $pluralLabel = 'Laporan CCTV Harian';
    
    protected static ?string $navigationLabel = 'Laporan CCTV Harian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('report_date')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('cctv_count')
                ->default(fn () => Cctv::count())
                ->suffix('CCTVs')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('active_cctv_count')
                    ->required()
                    ->default(fn () => Cctv::where('status', 0)->count()) //0 adalah aktif
                    ->numeric(),
                Forms\Components\TextInput::make('inactive_cctv_count')
                    ->required()
                    ->default(fn () => Cctv::where('status', 1)->count()) //1 adalah tidak aktif
                    ->numeric(),
                Forms\Components\Textarea::make('report_details')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_date')
                    ->label('Tanggal Laporan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cctv_count')
                    ->numeric()
                    ->label('Jumlah CCTV')
                    ->suffix(' CCTV')
                    ->sortable(),
                Tables\Columns\TextColumn::make('active_cctv_count')
                    ->numeric()
                    ->label('Jumlah CCTV Aktif')
                    ->suffix(' CCTV')
                    ->sortable(),
                Tables\Columns\TextColumn::make('inactive_cctv_count')
                    ->numeric()
                    ->label('Jumlah CCTV Tidak Aktif')
                    ->suffix(' CCTV')
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_details')
                    ->label('Detail Laporan')
                    ->wrap(),
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
            'index' => Pages\ListDailyCctvReports::route('/'),
            'create' => Pages\CreateDailyCctvReport::route('/create'),
            'edit' => Pages\EditDailyCctvReport::route('/{record}/edit'),
        ];
    }
}

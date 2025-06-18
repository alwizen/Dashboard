<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistributionResource\Pages;
use App\Filament\Resources\DistributionResource\RelationManagers;
use App\Models\Distribution;
use App\Models\Product; // Tambahkan import model Product
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistributionResource extends Resource
{
    protected static ?string $model = Distribution::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-top-right-on-square';

    protected static ?string $navigationGroup = 'RSD';

    protected static ?string $navigationLabel = 'Penyaluran';

    protected static ?string $label = 'Penyaluran BBM';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\DatePicker::make('distribution_date')
                    ->label('Tanggal Penyaluran')
                    ->default(now())
                    ->required(),
                TableRepeater::make('items')
                    ->relationship('items')
                    ->columns(2)
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->options(function (callable $get) {
                                // Ambil semua produk
                                $allProducts = Product::pluck('name', 'id')->toArray();
                                
                                // Ambil produk yang sudah dipilih di repeater items lain
                                $selectedProducts = collect($get('../../items'))
                                    ->pluck('product_id')
                                    ->filter()
                                    ->toArray();
                                
                                // Filter produk yang belum dipilih
                                return collect($allProducts)
                                    ->reject(function ($name, $id) use ($selectedProducts, $get) {
                                        // Jangan filter produk yang sedang dipilih di item saat ini
                                        $currentProductId = $get('product_id');
                                        return in_array($id, $selectedProducts) && $id != $currentProductId;
                                    })
                                    ->toArray();
                            })
                            ->reactive() // Membuat select reaktif terhadap perubahan
                            ->required(),
                        TextInput::make('value')
                            ->label('Jumlah Yang Disalurkan')
                            ->suffix('Kl')
                            ->numeric()
                            ->required(),
                    ])
                    ->addActionLabel('Tambah Item')
                    ->reorderableWithButtons()
                    ->collapsible(),
                Forms\Components\TextInput::make('note')
                    ->label('Catatan')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('distribution_date')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Penyaluran'),
                Tables\Columns\TextColumn::make('items.product.name')
                    ->listWithLineBreaks()
                    ->label('Produk'),
                Tables\Columns\TextColumn::make('items.value')
                    ->listWithLineBreaks()
                    ->suffix(' Kl')
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('note')
                    ->searchable()
                    ->label('Catatan'),
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
            'index' => Pages\ManageDistributions::route('/'),
        ];
    }
}
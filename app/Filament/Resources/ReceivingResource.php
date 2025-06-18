<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceivingResource\Pages;
use App\Filament\Resources\ReceivingResource\RelationManagers;
use App\Models\Receiving;
use App\Models\Product; // Pastikan import model Product
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use function Laravel\Prompts\select;

class ReceivingResource extends Resource
{
    protected static ?string $model = Receiving::class;
    protected static ?string $navigationLabel = 'Penerimaan';
    protected static ?string $label = 'Penerimaan BBM';
    protected static ?string $navigationGroup = 'RSD';
    // protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\DatePicker::make('receiving_date')
                    ->default(now())
                    ->label('Tanggal Penerimaan')
                    ->required(),
                TableRepeater::make('items')
                    ->relationship('items')
                    ->columns(3)
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
                            ->label('Jumlah Yang diterima')
                            ->suffix('Kl')
                            ->numeric()
                            ->required(),
                        TextInput::make('note')
                            ->label('Catatan')
                    ])
                    ->addActionLabel('Tambah Item')
                    ->reorderableWithButtons()
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('receiving_date')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Penerimaan'),
                Tables\Columns\TextColumn::make('items.product.name')
                    ->listWithLineBreaks()
                    ->label('Produk'),
                Tables\Columns\TextColumn::make('items.value')
                    ->listWithLineBreaks()
                    ->suffix(' Kl')
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('items.note')
                    ->listWithLineBreaks()
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

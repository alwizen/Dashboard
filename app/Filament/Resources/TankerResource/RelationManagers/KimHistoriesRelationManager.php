<?php

namespace App\Filament\Resources\TankerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KimHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'kimHistories';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('expiry_date')
                    ->required(),
                Forms\Components\FileUpload::make('document'),
                Forms\Components\Textarea::make('note')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expiry_date')
            ->columns([
                Tables\Columns\TextColumn::make('expiry_date'),
                Tables\Columns\ImageColumn::make('document'),
                Tables\Columns\TextColumn::make('note'),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
    // public static function afterCreate(): callable
    // {
    //     return function ($record, $livewire) {
    //         $tanker = $livewire->ownerRecord;

    //         if ($record->expiry_date > $tanker->kim_expiry) {
    //             $tanker->kim_expiry = $record->expiry_date;
    //             $tanker->save();
    //         }
    //     };
    // }

}

<?php

namespace App\Filament\Resources\MpsWorkingListResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgressHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'progressHistories';

    protected static ?string $title = '';    

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('progress_date')
                    ->label('Tanggal')
                    ->default(now())
                    ->required(),
                Forms\Components\TextInput::make('progress')
                    ->label('Progress')
                    ->suffix('%')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->label('Keterangan')
                    ->rows(3)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('progress')
            ->columns([
                Tables\Columns\TextColumn::make('progress')
                    ->suffix(' %'),
                Tables\Columns\TextColumn::make('progress_date')
                ->date('d-m-Y'),
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
}

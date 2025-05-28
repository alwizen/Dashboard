<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CriteriaTemplateResource\Pages;
use App\Filament\Resources\CriteriaTemplateResource\RelationManagers;
use App\Models\CriteriaTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CriteriaTemplateResource extends Resource
{
    protected static ?string $model = CriteriaTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Criteria Templates';

    protected static ?string $modelLabel = 'Criteria Template';

    protected static ?string $pluralModelLabel = 'Criteria Templates';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'New ✨';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('division_id')
                    ->relationship('division', 'name')
                    ->required()
                    ->preload(),
                Forms\Components\TextInput::make('field_name')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Internal field name (use snake_case)'),
                Forms\Components\Select::make('field_type')
                    ->required()
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'percentage' => 'Percentage',
                        'date' => 'Date',
                        'datetime' => 'Date Time',
                        'status' => 'Status',
                        'select' => 'Select',
                        'textarea' => 'Textarea',
                        'boolean' => 'Boolean (Yes/No)',
                    ])
                    ->live(),
                Forms\Components\KeyValue::make('field_options')
                    ->visible(fn (Forms\Get $get): bool => $get('field_type') === 'select')
                    ->helperText('Key-value pairs for select options'),
                Forms\Components\TextInput::make('unit')
                    ->maxLength(10)
                    ->helperText('Unit symbol (%, KL, etc)'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('display_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Order in forms (0 = first)'),
                Forms\Components\Toggle::make('is_required')
                    ->default(false),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('division.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('field_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('field_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'text', 'textarea' => 'gray',
                        'number', 'percentage' => 'info',
                        'date', 'datetime' => 'warning',
                        'select', 'status' => 'success',
                        'boolean' => 'primary',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('unit')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('display_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_required')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('division_id')
                    ->relationship('division', 'name')
                    ->preload(),
                Tables\Filters\SelectFilter::make('field_type')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'percentage' => 'Percentage',
                        'date' => 'Date',
                        'select' => 'Select',
                        'boolean' => 'Boolean',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('division_id')
            ->defaultSort('display_order');
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
            'index' => Pages\ListCriteriaTemplates::route('/'),
//            'view' => Pages\ViewCriteriaTemplate::route('/{record}'),
            'edit' => Pages\EditCriteriaTemplate::route('/{record}/edit'),
        ];
    }
}

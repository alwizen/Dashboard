<?php

namespace App\Filament\Resources\WorkListResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('progress')
                    ->label('Progress Baru (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->required()
                    ->helperText('Progress ini akan menggantikan progress utama program kerja.'),

                Forms\Components\RichEditor::make('note')
                    ->label('Catatan')
                    ->required()
                    ->placeholder('Jelaskan perkembangan atau update progress ini...'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('note')
            ->columns([
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->suffix('%')
                    ->sortable(),

                /* Tables\Columns\TextColumn::make('note') */
                /*     ->label('Catatan') */
                /*     ->limit(50) */
                /*     ->tooltip(function (Tables\Columns\TextColumn $column): ?string { */
                /*         $state = $column->getState(); */
                /*         if (strlen($state) <= 50) { */
                /*             return null; */
                /*         } */
                /*         return $state; */
                /*     }), */

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Progress Baru')
                    ->using(function (array $data): Model {
                        // Create history record
                        $history = $this->getRelationship()->create($data);

                        // Update parent WorkList progress
                        $workList = $this->getOwnerRecord();
                        $workList->progress = $data['progress'];

                        // Auto update status
                        if ($data['progress'] == 0) {
                            $workList->status = 'pending';
                        } elseif ($data['progress'] >= 100) {
                            $workList->status = 'completed';
                        } else {
                            $workList->status = 'in_progress';
                        }

                        $workList->save();

                        return $history;
                    })
                    ->after(function () {
                        // Refresh halaman setelah membuat history baru
                        $this->dispatch('refresh');

                        Notification::make()
                            ->title('Progress berhasil diperbarui')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->using(function (Model $record, array $data): Model {
                        // Update history record
                        $record->update($data);

                        // Update parent WorkList progress
                        $workList = $this->getOwnerRecord();
                        $workList->progress = $data['progress'];

                        // Auto update status
                        if ($data['progress'] == 0) {
                            $workList->status = 'pending';
                        } elseif ($data['progress'] >= 100) {
                            $workList->status = 'completed';
                        } else {
                            $workList->status = 'in_progress';
                        }

                        $workList->save();

                        return $record;
                    })
                    ->after(function () {
                        // Refresh setelah edit
                        $this->dispatch('refresh');

                        Notification::make()
                            ->title('History berhasil diperbarui')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus History Progress')
                    ->modalDescription('History ini akan dihapus, namun progress utama tidak akan berubah.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make()
                //         ->requiresConfirmation()
                //         ->modalHeading('Hapus History Terpilih')
                //         ->modalDescription('History yang dipilih akan dihapus, namun progress utama tidak akan berubah.'),
                // ]),
            ])
            ->emptyStateHeading('Belum ada history progress')
            ->emptyStateDescription('Tambah history baru untuk memperbarui progress program kerja.')
            ->emptyStateIcon('heroicon-o-chart-bar');
    }
}

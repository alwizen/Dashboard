<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TankerInspection;
use App\Models\Tanker;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;

class FollowupInspaction extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string $view = 'filament.pages.followup-inspaction';

    protected static ?string $navigationLabel = 'Tindak Lanjut Kekedapan';

    protected static ?string $title = 'Tindak Lanjut Kekedapan';

    // protected static ?string $navigationGroup = 'Inspeksi';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TankerInspection::query()
                    ->where('overall_status', 'tidak_kedap')
                    ->whereIn('id', function ($query) {
                        // Ambil ID inspeksi terbaru untuk setiap tanker
                        $query->selectRaw('MAX(id)')
                            ->from('tanker_inspections')
                            ->groupBy('tanker_id');
                    })
                    ->with('tanker')
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanker.nopol')
                    ->label('Nopol')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('tanker.merk')
                    ->label('Merk')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanker.capacity')
                    ->label('Kapasitas (KL)')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('tanker.comp')
                    ->label('Jumlah Komp')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('inspection_date')
                    ->label('Tgl Inspeksi Terakhir')
                    ->date()
                    ->sortable()
                    ->description(
                        fn(TankerInspection $record): string =>
                        $record->inspection_date->diffForHumans()
                    ),

                // Tables\Columns\ViewColumn::make('compartment_details')
                //     ->label('Detail Kompartemen')
                //     ->view('filament.tables.columns.compartment-status'),

                Tables\Columns\BadgeColumn::make('overall_status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'tidak_kedap',
                    ])
                    ->formatStateUsing(fn(string $state): string => 'Tidak Kedap'),

                Tables\Columns\TextColumn::make('tanker.status')
                    ->label('Status Tanker')
                    ->badge()
                    ->colors([
                        'success' => 'available',
                        'warning' => 'under_maintenance',
                        'danger' => 'afkir',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'available' => 'Available',
                        'under_maintenance' => 'Under Maintenance',
                        'afkir' => 'AFKIR',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan Inspeksi')
                    ->limit(50)
                    ->tooltip(function (TankerInspection $record): ?string {
                        return $record->notes;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tanker.merk')
                    ->label('Merk')
                    ->options(
                        Tanker::distinct()->pluck('merk', 'merk')->toArray()
                    ),

                Tables\Filters\SelectFilter::make('tanker.status')
                    ->label('Status Tanker')
                    ->options([
                        'available' => 'Available',
                        'under_maintenance' => 'Under Maintenance',
                        'afkir' => 'AFKIR',
                    ]),

                Tables\Filters\Filter::make('inspection_date')
                    ->form([
                        DatePicker::make('inspected_from')
                            ->label('Inspeksi Dari Tanggal'),
                        DatePicker::make('inspected_until')
                            ->label('Inspeksi Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['inspected_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('inspection_date', '>=', $date),
                            )
                            ->when(
                                $data['inspected_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('inspection_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('edit_inspection')
                        ->label('Edit Inspeksi')
                        ->icon('heroicon-m-pencil')
                        ->color('warning')
                        ->url(
                            fn(TankerInspection $record): string =>
                            route('filament.admin.resources.tanker-inspections.edit', $record)
                        ),
                    Tables\Actions\Action::make('new_inspection')
                        ->label('Inspeksi Ulang')
                        ->icon('heroicon-m-clipboard-document-check')
                        ->color('success')
                        ->url(
                            fn(TankerInspection $record): string =>
                            route('filament.admin.resources.tanker-inspections.create', [
                                'tanker_id' => $record->tanker_id
                            ])
                        ),

                    Tables\Actions\EditAction::make()
                        ->label('Edit Inspeksi')
                        ->icon('heroicon-m-pencil-square')
                        ->color('primary'),

                    Tables\Actions\Action::make('set_maintenance')
                        ->label('Set Under Maintenance')
                        ->icon('heroicon-m-wrench-screwdriver')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Set Status Under Maintenance')
                        ->modalDescription(
                            fn(TankerInspection $record) =>
                            "Apakah Anda yakin ingin mengubah status tanker {$record->tanker->nopol} menjadi Under Maintenance?"
                        )
                        ->action(function (TankerInspection $record) {
                            $record->tanker->update(['status' => 'under_maintenance']);

                            Notification::make()
                                ->title('Status tanker berhasil diubah')
                                ->body("Tanker {$record->tanker->nopol} telah diset menjadi Under Maintenance")
                                ->success()
                                ->send();
                        })
                        ->visible(
                            fn(TankerInspection $record) =>
                            $record->tanker->status !== 'under_maintenance'
                        ),
                ])
            ])
            ->headerActions([
                // Action::make('export_report')
                //     ->label('Export Laporan')
                //     ->icon('heroicon-m-document-arrow-down')
                //     ->color('gray')
                //     ->action(function () {
                //         Notification::make()
                //             ->title('Export dalam pengembangan')
                //             ->body('Fitur export laporan sedang dalam pengembangan')
                //             ->info()
                //             ->send();
                //     }),
            ])
            ->defaultSort('inspection_date', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-m-arrow-path')
                ->action(function () {
                    $this->resetTable();

                    Notification::make()
                        ->title('Data berhasil di-refresh')
                        ->success()
                        ->send();
                }),
        ];
    }

    // public function getHeading(): string
    // {
    //     $count = $this->getTableQuery()->count();
    //     return "Tindak Lanjut Kekedapan ({$count} Tanker Tidak Kedap)";
    // }

    protected function getHeaderWidgets(): array
    {
        return [
            // Bisa tambahkan widget statistik di sini jika diperlukan
        ];
    }
}

<?php

namespace App\Filament\Resources\TankerResource\Pages;

use App\Filament\Resources\TankerResource;
use App\Models\Tanker;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\ExportAction;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Filament\Resources\Pages\ListRecords;

class ListTankers extends ListRecords
{
    protected static string $resource = TankerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(\App\Filament\Exports\TankerExporter::class)
                ->label('Export Tankers')
                ->icon('heroicon-o-cloud-arrow-up'),
            ImportAction::make()
                ->importer(\App\Filament\Imports\TankerImporter::class)
                ->label('Import Tankers')
                ->icon('heroicon-o-cloud-arrow-down'),
            Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // === NOTIFIKASI KIR ===
        $kirExpiring = Tanker::whereDate('kir_expiry', '<=', now()->addDays(7))
            ->get();

        if ($kirExpiring->isNotEmpty()) {
            // Pisahkan berdasarkan status
            $kirExpiredList = [];
            $kirWarningList = [];

            foreach ($kirExpiring as $t) {
                $today = now()->startOfDay();
                $expiryDate = Carbon::parse($t->kir_expiry)->startOfDay();
                $days = $today->diffInDays($expiryDate, false);

                if ($days < 0) {
                    // Sudah expired
                    $dayText = "sudah expired " . abs($days) . " hari yang lalu";
                    $kirExpiredList[] = " {$t->nopol} ({$dayText})";
                } elseif ($days == 0) {
                    // Expired hari ini
                    $dayText = "expired hari ini";
                    $kirExpiredList[] = " {$t->nopol} ({$dayText})";
                } else {
                    //  expired
                    $dayText = "expired dalam {$days} hari";
                    $kirWarningList[] = " {$t->nopol} ({$dayText})";
                }
            }

            // Notifikasi untuk yang sudah expired (DANGER)
            if (!empty($kirExpiredList)) {
                $kirExpiredText = implode("\n", $kirExpiredList);
                Notification::make()
                    ->title('🚨 KIR Sudah Berakhir')
                    ->body("Mobil tangki berikut KIR-nya sudah berakhir:\n\n{$kirExpiredText}")
                    ->danger()
                    ->seconds(15)
                    ->actions([
                        Action::make('Edit')
                            ->button()
                            ->url(route('filament.admin.resources.tankers.edit', ['record' => $t->id]), shouldOpenInNewTab: true),
                    
                    ])
                    ->send();
            }

            // Notifikasi untuk yang  expired (WARNING)
            if (!empty($kirWarningList)) {
                $kirWarningText = implode("\n", $kirWarningList);
                Notification::make()
                    ->title('⚠️ KIR  Berakhir')
                    ->body("Mobil tangki berikut KIR-nya  berakhir:\n\n{$kirWarningText}")
                    ->seconds(15)
                    ->warning()
                    ->actions([
                        Action::make('Edit')
                            ->button()
                            ->url(route('filament.admin.resources.tankers.edit', ['record' => $t->id]), shouldOpenInNewTab: true),
                    
                    ])
                    ->send();
            }
        }

        // === NOTIFIKASI KIM ===
        $kimExpiring = Tanker::whereDate('kim_expiry', '<=', now()->addDays(7))
            ->get();

        if ($kimExpiring->isNotEmpty()) {
            // Pisahkan berdasarkan status
            $kimExpiredList = [];
            $kimWarningList = [];

            foreach ($kimExpiring as $t) {
                $today = now()->startOfDay();
                $expiryDate = Carbon::parse($t->kim_expiry)->startOfDay();
                $days = $today->diffInDays($expiryDate, false);

                if ($days < 0) {
                    // Sudah expired
                    $dayText = "sudah expired " . abs($days) . " hari yang lalu";
                    $kimExpiredList[] = " {$t->nopol} ({$dayText})";
                } elseif ($days == 0) {
                    // Expired hari ini
                    $dayText = "expired hari ini";
                    $kimExpiredList[] = " {$t->nopol} ({$dayText})";
                } else {
                    //  expired
                    $dayText = "expired dalam {$days} hari";
                    $kimWarningList[] = " {$t->nopol} ({$dayText})";
                }
            }

            // Notifikasi untuk yang sudah expired (DANGER)
            if (!empty($kimExpiredList)) {
                $kimExpiredText = implode("\n", $kimExpiredList);
                Notification::make()
                    ->title('🚨 KIM Sudah Berakhir')
                    ->body("Mobil tangki berikut KIM-nya sudah berakhir:\n\n{$kimExpiredText}")
                    ->seconds(15)
                    ->danger()
                    ->actions([
                        Action::make('Edit')
                            ->button()
                            ->url(route('filament.admin.resources.tankers.edit', ['record' => $t->id]), shouldOpenInNewTab: true),
                       
                    ])
                    ->send();
            }

            // Notifikasi untuk yang  expired (WARNING)
            if (!empty($kimWarningList)) {
                $kimWarningText = implode("\n", $kimWarningList);
                Notification::make()
                    ->title('⚠️ KIM  Berakhir')
                    ->body("Mobil tangki berikut KIM-nya  berakhir:\n\n{$kimWarningText}")
                    ->seconds(15)
                    ->warning()
                    ->actions([
                        Action::make('Edit')
                            ->button()
                            ->url(route('filament.admin.resources.tankers.edit', ['record' => $t->id]), shouldOpenInNewTab: true),
                    
                    ])
                    ->send();
            }
        }
    }
}

<?php

namespace App\Filament\Resources\TankerBbkResource\Pages;

use App\Filament\Resources\TankerBbkResource;
use App\Models\TankerBbk;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Carbon;

class ManageTankerBbks extends ManageRecords
{
    protected static string $resource = TankerBbkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah MT BBK'),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // === NOTIFIKASI KIR ===
        $kirExpiring = TankerBbk::whereDate('kir_expiry', '<=', now()->addDays(7))
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
                    ->send();
            }
        }

        // === NOTIFIKASI KIM ===
        $kimExpiring = TankerBbk::whereDate('kim_expiry', '<=', now()->addDays(7))
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
                    ->send();
            }
        }
    }
}

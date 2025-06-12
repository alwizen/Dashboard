<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TankerKirHistory extends Model
{
    protected $fillable = [
        'tanker_id',
        'expiry_date',
        'document',
        'note'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public static function booted()
    {
        // Event ketika record dibuat atau diupdate
        static::saved(function ($kir) {
            self::updateTankerKirExpiry($kir->tanker_id);
        });

        // Event ketika record dihapus
        static::deleted(function ($kir) {
            self::updateTankerKirExpiry($kir->tanker_id);
        });
    }

    /**
     * Update kir_expiry di tanker dengan tanggal terbaru
     */
    private static function updateTankerKirExpiry($tankerId)
    {
        $latestDate = self::where('tanker_id', $tankerId)
            ->max('expiry_date');

        // Debug logging (hapus setelah selesai debug)
        // \Log::info('Updating KIR expiry', [
        //     'tanker_id' => $tankerId,
        //     'latest_date' => $latestDate
        // ]);

        // Update tanker dengan tanggal terbaru, atau null jika tidak ada history
        $updated = \App\Models\Tanker::where('id', $tankerId)
            ->update(['kir_expiry' => $latestDate]);

        // \Log::info('KIR expiry updated', ['rows_affected' => $updated]);
    }

    public function tanker()
    {
        return $this->belongsTo(Tanker::class);
    }
}
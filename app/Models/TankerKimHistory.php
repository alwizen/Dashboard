<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TankerKimHistory extends Model
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
      static::saved(function ($kim) {
         self::updateTankerKimExpiry($kim->tanker_id);
      });

      // Event ketika record dihapus
      static::deleted(function ($kim) {
         self::updateTankerKimExpiry($kim->tanker_id);
      });
   }

   /**
    * Update kim_expiry di tanker dengan tanggal terbaru
    */
   private static function updateTankerKimExpiry($tankerId)
   {
      $latestDate = self::where('tanker_id', $tankerId)
         ->max('expiry_date');

      // Update tanker dengan tanggal terbaru, atau null jika tidak ada history
      \App\Models\Tanker::where('id', $tankerId)
         ->update(['kim_expiry' => $latestDate]);
   }

   public function tanker()
   {
      return $this->belongsTo(Tanker::class);
   }
}

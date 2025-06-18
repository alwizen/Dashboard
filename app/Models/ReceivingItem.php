<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivingItem extends Model
{
    protected $fillable = [
        'receiving_id',
        'product_id',
        'value',
        'note'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function receiving(): BelongsTo
    {
        return $this->belongsTo(Receiving::class);
    }
}

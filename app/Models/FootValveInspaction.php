<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootValveInspaction extends Model
{
    protected $fillable = ['tanker_id', 'date', 'photo1', 'photo2', 'photo3', 'photo4','note'];

    public function tanker()
    {
        return $this->belongsTo(Tanker::class);
    }

    public function transportir()
    {
        return $this->tanker?->transportir(); // indirect relation
    }
}


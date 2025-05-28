<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'program_id',
        'field_changed',
        'old_value',
        'new_value',
        'changed_by',
        'changed_at'
    ];

    protected $casts = [
        'changed_at' => 'datetime'
    ];

    // Relationships
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

//    public function user(): BelongsTo
//    {
//        return $this->belongsTo(User::class, 'changed_by');
//    }

    // Scopes
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('changed_at', '>=', now()->subDays($days));
    }

    public function scopeByField($query, $field)
    {
        return $query->where('field_changed', $field);
    }
}

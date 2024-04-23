<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageCapsule extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'message', 'open_date', 'is_opened'];
    protected $casts = [
        'open_date' => 'datetime',
    ];


    // Relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnopened($query)
    {
        return $query->where('is_opened', false);
    }
}


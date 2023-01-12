<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model {
    protected $fillable = [
        'name',
        'completed',
        'description',
        'type',
        'deadline',
        'user_id',
    ];
    protected $casts = [
        'completed' => 'boolean',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}

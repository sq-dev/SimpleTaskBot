<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $fillable = [
        'name',
        'telegram_id',
        'notifications',
        'last_activity'
    ];
    protected $casts = [
        'notifications' => 'boolean',
        'last_activity' => 'datetime'
    ];

    public function tasks(): HasMany {
        return $this->hasMany(Task::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    public function isTechnician(): bool
    {
        return $this->role === 'teknisi';
    }

    // Pelanggan yang didaftarkan user ini (sebagai sales)
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Tugas survei yang ditugaskan ke user ini (sebagai teknisi)
    public function assignedSubscriptions()
    {
        return $this->hasMany(Subscription::class, 'technician_id');
    }
}
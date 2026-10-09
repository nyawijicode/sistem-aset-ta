<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // kedua role boleh login ke panel
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'foto_perfil',
        'is_admin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    public function topicos(): HasMany
    {
        return $this->hasMany(Topico::class);
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class);
    }

    public function getFotoPerfilUrlAttribute(): ?string
    {
        if (! $this->foto_perfil || ! Storage::disk('public')->exists($this->foto_perfil)) {
            return null;
        }

        return '/storage/'.ltrim($this->foto_perfil, '/');
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
}

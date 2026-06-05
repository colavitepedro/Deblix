<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Topico extends Model
{
    public const CATEGORIAS = [
        'Tecnologia',
        'Jogos',
        'Estudos',
        'Dúvidas',
        'Notícias',
        'Geral',
    ];

    protected $fillable = [
        'user_id',
        'titulo',
        'categoria',
        'autor',
        'descricao',
        'data_criacao',
        'imagem',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'data_criacao' => 'date',
    ];

    public function dono(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class);
    }

    public function pertenceAo(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($this->user_id !== null) {
            return $this->user_id === $user->id;
        }

        return $this->autor === $user->name;
    }

    public function getImagemUrlAttribute(): ?string
    {
        if (! $this->imagem || ! Storage::disk('public')->exists($this->imagem)) {
            return null;
        }

        return '/storage/'.ltrim($this->imagem, '/');
    }
}

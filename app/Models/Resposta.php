<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resposta extends Model
{
    protected $fillable = [
        'topico_id',
        'user_id',
        'usuario',
        'mensagem',
        'data_resposta',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'data_resposta' => 'date',
    ];

    public function topico(): BelongsTo
    {
        return $this->belongsTo(Topico::class);
    }

    public function dono(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pertenceAo(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($this->user_id !== null) {
            return $this->user_id === $user->id;
        }

        return $this->usuario === $user->name;
    }
}

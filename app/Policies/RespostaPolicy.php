<?php

namespace App\Policies;

use App\Models\Resposta;
use App\Models\User;

class RespostaPolicy
{
    public function update(User $user, Resposta $resposta): bool
    {
        return $user->isAdmin() || $resposta->pertenceAo($user);
    }

    public function delete(User $user, Resposta $resposta): bool
    {
        return $this->update($user, $resposta);
    }
}

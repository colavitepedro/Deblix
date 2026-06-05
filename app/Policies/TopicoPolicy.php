<?php

namespace App\Policies;

use App\Models\Topico;
use App\Models\User;

class TopicoPolicy
{
    public function update(User $user, Topico $topico): bool
    {
        return $user->isAdmin() || $topico->pertenceAo($user);
    }

    public function delete(User $user, Topico $topico): bool
    {
        return $this->update($user, $topico);
    }

    public function responder(User $user, Topico $topico): bool
    {
        return true;
    }
}

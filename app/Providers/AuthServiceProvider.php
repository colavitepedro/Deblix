<?php

namespace App\Providers;

use App\Models\Resposta;
use App\Models\Topico;
use App\Policies\RespostaPolicy;
use App\Policies\TopicoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Topico::class => TopicoPolicy::class,
        Resposta::class => RespostaPolicy::class,
    ];

    public function boot(): void {}
}

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Deblix')</title>
    @php
        $stylePath = public_path('css/deblix.css');
        $styleVersion = file_exists($stylePath) ? filemtime($stylePath) : time();
        $navCategorias = \App\Models\Topico::CATEGORIAS;
    @endphp
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/deblix.css') }}?v={{ $styleVersion }}">
</head>
<body>
<header class="app-header sticky-top">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand brand-mark" href="{{ route('topicos.index') }}" aria-label="Deblix">
                <span class="brand-symbol">D</span>
                <span>Deblix</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('topicos.index') }}">
                            <i class="bi bi-house-door"></i> Início
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-grid"></i> Categorias
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            @foreach($navCategorias as $categoria)
                                <li>
                                    <a class="dropdown-item" href="{{ route('topicos.index', ['categoria' => $categoria]) }}">{{ $categoria }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.show') }}">
                                <i class="bi bi-person-circle"></i> Meu Perfil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('topicos.create') }}">
                                <i class="bi bi-plus-circle"></i> Novo tópico
                            </a>
                        </li>
                    @endauth
                </ul>

                <div class="nav-actions">
                    @guest
                        <a class="btn btn-ghost" href="{{ route('login') }}">Entrar</a>
                        <a class="btn btn-primary-deblix" href="{{ route('register') }}">Cadastrar</a>
                    @else
                        <a class="user-chip" href="{{ route('profile.show') }}">
                            <span class="user-chip-avatar">
                                @if(auth()->user()->foto_perfil_url)
                                    <img src="{{ auth()->user()->foto_perfil_url }}" alt="Foto de {{ auth()->user()->name }}">
                                @else
                                    {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </span>
                            <span class="user-chip-name">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isAdmin())
                                <span class="admin-chip">Super usuário</span>
                            @endif
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-ghost" type="submit">Sair</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="app-main">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success app-alert" role="alert">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger app-alert" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif
    </div>

    @yield('content')
</main>

<footer class="app-footer">
    <div class="container text-center">
        <span>Deblix &copy; {{ date('Y') }}</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

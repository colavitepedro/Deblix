@extends('layouts.app')

@section('title', 'Deblix - Comunidade')

@section('content')
<section class="feed-header">
    <div class="container">
        <div class="feed-header-bar">
            <h1>Discussões da comunidade</h1>

            <div class="feed-header-actions">
                @auth
                    <a class="btn btn-primary-deblix" href="{{ route('topicos.create') }}">
                        <i class="bi bi-plus-circle"></i> Novo tópico
                    </a>
                @else
                    <a class="btn btn-primary-deblix" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </a>
                @endauth
            </div>
        </div>

        <div class="feed-metrics">
            <span><strong>{{ $totalTopicos }}</strong> tópicos</span>
            <span><strong>{{ $totalRespostas }}</strong> comentários</span>
            <span><strong>{{ count($categorias) }}</strong> categorias</span>
        </div>
    </div>
</section>

<section class="topics-section" id="topicos">
    <div class="container">
        <form class="filter-panel" method="GET" action="{{ route('topicos.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <label class="form-label" for="busca">Buscar tópicos</label>
                    <div class="input-icon">
                        <i class="bi bi-search"></i>
                        <input class="form-control" type="search" id="busca" name="busca" value="{{ $busca }}" placeholder="Título, descrição ou autor">
                    </div>
                </div>

                <div class="col-md-4 col-lg-3">
                    <label class="form-label" for="categoria">Categoria</label>
                    <select class="form-select" id="categoria" name="categoria">
                        <option value="">Todas</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected($categoriaAtual === $categoria)>{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 col-lg-2">
                    <label class="form-label" for="ordenacao">Ordenar</label>
                    <select class="form-select" id="ordenacao" name="ordenacao">
                        <option value="recentes" @selected($ordenacao === 'recentes')>Mais recentes</option>
                        <option value="antigos" @selected($ordenacao === 'antigos')>Mais antigos</option>
                        <option value="mais_respondidos" @selected($ordenacao === 'mais_respondidos')>Mais respondidos</option>
                    </select>
                </div>

                <div class="col-md-4 col-lg-2 d-grid">
                    <button class="btn btn-primary-deblix" type="submit">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        <div class="category-strip" aria-label="Categorias">
            <a class="category-link {{ $categoriaAtual ? '' : 'active' }}" href="{{ route('topicos.index', ['busca' => $busca, 'ordenacao' => $ordenacao]) }}">Todas</a>
            @foreach($categorias as $categoria)
                <a class="category-link {{ $categoriaAtual === $categoria ? 'active' : '' }}" href="{{ route('topicos.index', ['busca' => $busca, 'categoria' => $categoria, 'ordenacao' => $ordenacao]) }}">
                    {{ $categoria }}
                </a>
            @endforeach
        </div>

        <div class="section-heading">
            <div>
                <h2>{{ $categoriaAtual ? 'Tópicos em ' . $categoriaAtual : 'Tópicos recentes' }}</h2>
                <span>{{ $topicos->total() }} {{ $topicos->total() === 1 ? 'resultado' : 'resultados' }}</span>
            </div>
            @if($busca || $categoriaAtual)
                <a class="clear-link" href="{{ route('topicos.index') }}">Limpar filtros</a>
            @endif
        </div>

        <div class="topics-list">
            @forelse($topicos as $topico)
                @include('partials.topico-card', ['topico' => $topico])
            @empty
                <div class="empty-state">
                    <i class="bi bi-chat-square-text"></i>
                    <h3>Nenhum tópico encontrado</h3>
                    @auth
                        <a class="btn btn-primary-deblix mt-2" href="{{ route('topicos.create') }}">Criar tópico</a>
                    @else
                        <a class="btn btn-primary-deblix mt-2" href="{{ route('register') }}">Criar conta</a>
                    @endauth
                </div>
            @endforelse
        </div>

        <div class="pagination-wrap">
            {{ $topicos->links() }}
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('title', $topico->titulo . ' - Deblix')

@section('content')
@php
    $autorNome = $topico->dono?->name ?? $topico->autor;
    $autorFoto = $topico->dono?->foto_perfil_url;
@endphp

<section class="discussion-page">
    <div class="container">
        <a class="back-link discussion-back" href="{{ route('topicos.index') }}">
            <i class="bi bi-arrow-left"></i> Voltar para discussões
        </a>

        <div class="discussion-layout">
            <main class="discussion-main">
                <article class="discussion-post">
                    <div class="post-author-row">
                        <span class="response-avatar">
                            @if($autorFoto)
                                <img src="{{ $autorFoto }}" alt="Foto de {{ $autorNome }}">
                            @else
                                {{ mb_strtoupper(mb_substr($autorNome, 0, 1)) }}
                            @endif
                        </span>
                        <div>
                            <strong>{{ $autorNome }}</strong>
                            <span>publicou em {{ $topico->categoria }} · {{ $topico->data_criacao?->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <h1>{{ $topico->titulo }}</h1>
                    <div class="post-description">{{ $topico->descricao }}</div>

                    @if($topico->imagem_url)
                        <a class="post-image" href="{{ $topico->imagem_url }}" target="_blank" rel="noopener">
                            <img src="{{ $topico->imagem_url }}" alt="Imagem do tópico {{ $topico->titulo }}">
                        </a>
                    @endif

                    <div class="post-action-bar">
                        <span><i class="bi bi-chat-square-text"></i> {{ $topico->respostas_count }} {{ $topico->respostas_count === 1 ? 'comentário' : 'comentários' }}</span>

                        @can('update', $topico)
                            <a href="{{ route('topicos.edit', $topico) }}"><i class="bi bi-pencil"></i> Editar</a>
                        @endcan
                        @can('delete', $topico)
                            <form method="POST" action="{{ route('topicos.destroy', $topico) }}" onsubmit="return confirm('Tem certeza que deseja excluir esta discussão e todos os comentários?');">
                                @csrf
                                @method('DELETE')
                                <button class="danger-action" type="submit"><i class="bi bi-trash"></i> Excluir</button>
                            </form>
                        @endcan
                    </div>
                </article>

                <section class="comments-section" id="comentarios">
                    <div class="comments-toolbar">
                        <div>
                            <h2>Comentários</h2>
                            <span>{{ $topico->respostas_count }} {{ $topico->respostas_count === 1 ? 'participação' : 'participações' }}</span>
                        </div>
                    </div>

                    @auth
                        <form class="comment-composer" id="responder" method="POST" action="{{ route('respostas.store', $topico) }}">
                            @csrf
                            <div class="comment-composer-user">
                                <span class="response-avatar">
                                    @if(auth()->user()->foto_perfil_url)
                                        <img src="{{ auth()->user()->foto_perfil_url }}" alt="Foto de {{ auth()->user()->name }}">
                                    @else
                                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                    @endif
                                </span>
                                <span>Comentando como <strong>{{ auth()->user()->name }}</strong></span>
                            </div>
                            <label class="visually-hidden" for="mensagem">Escreva seu comentário</label>
                            <textarea class="form-control @error('mensagem') is-invalid @enderror" id="mensagem" name="mensagem" rows="4" maxlength="5000" placeholder="Escreva um comentário">{{ old('mensagem') }}</textarea>
                            @error('mensagem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="comment-composer-footer">
                                <span><span id="comment-count">0</span>/5000 caracteres</span>
                                <button class="btn btn-primary-deblix" type="submit">
                                    <i class="bi bi-send"></i> Comentar
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="guest-panel compact-guest">
                            <strong>Entre para comentar</strong>
                            <a class="btn btn-primary-deblix" href="{{ route('login') }}">Entrar</a>
                        </div>
                    @endauth

                    <div class="comments-list">
                        @forelse($topico->respostas as $resposta)
                            @php
                                $respostaAutorNome = $resposta->dono?->name ?? $resposta->usuario;
                                $respostaAutorFoto = $resposta->dono?->foto_perfil_url;
                                $respostaAutorAdmin = (bool) $resposta->dono?->isAdmin();
                                $respostaAutorTopico = $topico->user_id && $resposta->user_id && $topico->user_id === $resposta->user_id;
                            @endphp

                            <article class="comment-item" id="resposta-{{ $resposta->id }}">
                                <header class="comment-head">
                                    <span class="response-avatar">
                                        @if($respostaAutorFoto)
                                            <img src="{{ $respostaAutorFoto }}" alt="Foto de {{ $respostaAutorNome }}">
                                        @else
                                            {{ mb_strtoupper(mb_substr($respostaAutorNome, 0, 1)) }}
                                        @endif
                                    </span>
                                    <div>
                                        <div class="response-author-line">
                                            <strong>{{ $respostaAutorNome }}</strong>
                                            @if($respostaAutorTopico)
                                                <span class="role-pill author">Autor</span>
                                            @endif
                                            @if($respostaAutorAdmin)
                                                <span class="role-pill admin">Super usuário</span>
                                            @endif
                                        </div>
                                        <span>{{ $resposta->created_at?->timezone('America/Sao_Paulo')->format('d/m/Y \à\s H:i') ?? $resposta->data_resposta?->format('d/m/Y') }}</span>
                                    </div>
                                </header>

                                <div class="comment-message">{{ $resposta->mensagem }}</div>

                                <footer class="comment-actions">
                                    @auth
                                        <button type="button" class="reply-to-comment" data-reply-to="{{ $respostaAutorNome }}">
                                            <i class="bi bi-reply"></i> Responder
                                        </button>
                                    @endauth
                                    @can('update', $resposta)
                                        <a href="{{ route('respostas.edit', $resposta) }}"><i class="bi bi-pencil"></i> Editar</a>
                                    @endcan
                                    @can('delete', $resposta)
                                        <form method="POST" action="{{ route('respostas.destroy', $resposta) }}" onsubmit="return confirm('Tem certeza que deseja excluir este comentário?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="danger-action" type="submit"><i class="bi bi-trash"></i> Excluir</button>
                                        </form>
                                    @endcan
                                </footer>
                            </article>
                        @empty
                            <div class="empty-state comments-empty">
                                <i class="bi bi-chat-square-text"></i>
                                <h3>Nenhum comentário ainda</h3>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>

            <aside class="discussion-sidebar">
                <div class="sidebar-panel">
                    <h3>Discussão</h3>
                    <dl>
                        <div>
                            <dt>Categoria</dt>
                            <dd>{{ $topico->categoria }}</dd>
                        </div>
                        <div>
                            <dt>Comentários</dt>
                            <dd>{{ $topico->respostas_count }}</dd>
                        </div>
                    </dl>
                    @auth
                        <a class="btn btn-primary-deblix w-100" href="#responder"><i class="bi bi-chat-left-text"></i> Escrever comentário</a>
                    @endauth
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const messageField = document.getElementById('mensagem');
    const commentCount = document.getElementById('comment-count');

    if (messageField && commentCount) {
        const updateCount = () => commentCount.textContent = messageField.value.length;
        updateCount();
        messageField.addEventListener('input', updateCount);

        document.querySelectorAll('[data-reply-to]').forEach((button) => {
            button.addEventListener('click', () => {
                const mention = `@${button.dataset.replyTo} `;

                if (!messageField.value.startsWith(mention)) {
                    messageField.value = mention + messageField.value;
                }

                updateCount();
                messageField.focus();
                messageField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    }
</script>
@endpush

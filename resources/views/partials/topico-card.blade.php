@php
    $autorNome = $topico->dono?->name ?? $topico->autor;
    $autorFoto = $topico->dono?->foto_perfil_url;
    $respostasCount = $topico->respostas_count ?? $topico->respostas()->count();
@endphp

<article class="topic-card">
    <div class="topic-content">
        <div class="topic-meta">
            <span class="topic-author-mini">
                <span class="mini-avatar">
                    @if($autorFoto)
                        <img src="{{ $autorFoto }}" alt="Foto de {{ $autorNome }}">
                    @else
                        {{ mb_strtoupper(mb_substr($autorNome, 0, 1)) }}
                    @endif
                </span>
                {{ $autorNome }}
            </span>
            <span>em {{ $topico->categoria }}</span>
            <span>·</span>
            <span>{{ $topico->data_criacao?->format('d/m/Y') }}</span>
        </div>

        <h2><a href="{{ route('topicos.show', $topico) }}">{{ $topico->titulo }}</a></h2>
        <p>{{ \Illuminate\Support\Str::limit($topico->descricao, 190) }}</p>

        <div class="topic-footer">
            <a class="reply-count" href="{{ route('topicos.show', $topico) }}#comentarios">
                <i class="bi bi-chat-square-text"></i>
                {{ $respostasCount }} {{ $respostasCount === 1 ? 'comentário' : 'comentários' }}
            </a>

            <div class="topic-actions">
                <a class="btn btn-sm btn-outline-light-deblix" href="{{ route('topicos.show', $topico) }}">Abrir discussão</a>
                @can('update', $topico)
                    <a class="btn btn-sm btn-ghost" href="{{ route('topicos.edit', $topico) }}" title="Editar tópico" aria-label="Editar tópico"><i class="bi bi-pencil"></i></a>
                @endcan
            </div>
        </div>
    </div>

    <a class="topic-media" href="{{ route('topicos.show', $topico) }}" aria-label="Abrir tópico {{ $topico->titulo }}">
        @if($topico->imagem_url)
            <img src="{{ $topico->imagem_url }}" alt="Imagem do tópico {{ $topico->titulo }}">
        @else
            <span class="topic-placeholder"><i class="bi bi-chat-square-text"></i></span>
        @endif
    </a>
</article>

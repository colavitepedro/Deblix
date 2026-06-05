@extends('layouts.app')

@section('title', 'Meu Perfil - Deblix')

@section('content')
<section class="page-section">
    <div class="container narrow-container">
        <div class="page-title">
            <h1>Meu Perfil</h1>
        </div>

        <div class="profile-card">
            <div class="profile-avatar">
                @if($user->foto_perfil_url)
                    <img src="{{ $user->foto_perfil_url }}" alt="Foto de perfil de {{ $user->name }}">
                @else
                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                @endif
            </div>

            <div class="profile-info">
                <div class="profile-title-row">
                    <h2>{{ $user->name }}</h2>
                    @if($user->isAdmin())
                        <span class="admin-badge"><i class="bi bi-shield-check"></i> Super usuário</span>
                    @endif
                </div>
                <dl>
                    <div>
                        <dt>E-mail</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt>Data de cadastro</dt>
                        <dd>{{ $user->created_at?->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="metric-grid profile-metrics mt-4">
            <div class="metric-card">
                <span>Tópicos criados</span>
                <strong>{{ $totalTopicos }}</strong>
            </div>
            <div class="metric-card">
                <span>Comentários enviados</span>
                <strong>{{ $totalRespostas }}</strong>
            </div>
        </div>

        <div class="content-grid mt-4">
            <section class="form-panel">
                <div class="section-heading compact-heading">
                    <h2>Minhas discussões</h2>
                </div>

                <div class="simple-list">
                    @forelse($meusTopicos as $topico)
                        <a class="simple-list-item" href="{{ route('topicos.show', $topico) }}">
                            <div>
                                <strong>{{ $topico->titulo }}</strong>
                                <span>{{ $topico->categoria }} · {{ $topico->respostas_count }} {{ $topico->respostas_count === 1 ? 'comentário' : 'comentários' }}</span>
                            </div>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @empty
                        <div class="empty-state compact">
                            <i class="bi bi-chat-square-text"></i>
                            <h3>Nenhuma discussão criada</h3>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="form-panel">
                <div class="section-heading compact-heading">
                    <h2>Meus comentários</h2>
                </div>

                <div class="simple-list">
                    @forelse($minhasRespostas as $resposta)
                        <a class="simple-list-item" href="{{ route('topicos.show', $resposta->topico) }}">
                            <div>
                                <strong>{{ \Illuminate\Support\Str::limit($resposta->mensagem, 70) }}</strong>
                                <span>{{ $resposta->topico?->titulo ?? 'Tópico removido' }} · {{ $resposta->data_resposta?->format('d/m/Y') }}</span>
                            </div>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @empty
                        <div class="empty-state compact">
                            <i class="bi bi-chat-dots"></i>
                            <h3>Nenhum comentário enviado</h3>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <form class="form-panel mt-4" method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <h2 class="form-section-title">Dados do perfil</h2>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Usuário</label>
                    <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">E-mail</label>
                    <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button class="btn btn-primary-deblix mt-3" type="submit">
                <i class="bi bi-check2"></i> Salvar dados
            </button>
        </form>

        <form class="form-panel mt-4" method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h2 class="form-section-title">Foto de perfil</h2>
            <p class="help-text">JPG, PNG ou WEBP · até 2 MB</p>

            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label" for="foto_perfil">Selecionar imagem</label>
                    <input class="form-control @error('foto_perfil') is-invalid @enderror" type="file" id="foto_perfil" name="foto_perfil" accept="image/png,image/jpeg,image/webp">
                    @error('foto_perfil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 d-grid">
                    <button class="btn btn-primary-deblix" type="submit">
                        <i class="bi bi-upload"></i> Salvar foto
                    </button>
                </div>
            </div>
        </form>

        <div class="form-actions mt-4">
            <a class="btn btn-primary-deblix" href="{{ route('topicos.create') }}">
                <i class="bi bi-plus-circle"></i> Criar tópico
            </a>
            <a class="btn btn-ghost" href="{{ route('topicos.index') }}">
                <i class="bi bi-arrow-left"></i> Voltar ao início
            </a>
        </div>
    </div>
</section>
@endsection

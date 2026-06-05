@extends('layouts.app')

@section('title', 'Editar comentário - Deblix')

@section('content')
<section class="page-section">
    <div class="container narrow-container">
        <a class="back-link" href="{{ route('topicos.show', $resposta->topico) }}">
            <i class="bi bi-arrow-left"></i> Voltar para o tópico
        </a>

        <div class="page-title">
            <h1>Editar comentário</h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger app-alert" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                Revise o comentário.
            </div>
        @endif

        <form class="form-panel" method="POST" action="{{ route('respostas.update', $resposta) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <div class="system-info-grid">
                        <div class="system-field">
                            <span>Usuário</span>
                            <strong>{{ $resposta->usuario }}</strong>
                        </div>
                        <div class="system-field">
                            <span>Data</span>
                            <strong>{{ $resposta->data_resposta?->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label" for="mensagem">Comentário</label>
                    <textarea class="form-control @error('mensagem') is-invalid @enderror" id="mensagem" name="mensagem" rows="7" maxlength="5000">{{ old('mensagem', $resposta->mensagem) }}</textarea>
                    @error('mensagem')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary-deblix" type="submit">
                    <i class="bi bi-check2"></i> Salvar comentário
                </button>
                <a class="btn btn-ghost" href="{{ route('topicos.show', $resposta->topico) }}">Cancelar</a>
            </div>
        </form>
    </div>
</section>
@endsection

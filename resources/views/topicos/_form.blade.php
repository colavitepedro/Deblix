@if($errors->any())
    <div class="alert alert-danger app-alert" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        Revise os campos destacados.
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-panel">
            <div class="mb-3">
                <label class="form-label" for="titulo">Título</label>
                <input class="form-control @error('titulo') is-invalid @enderror" type="text" id="titulo" name="titulo" value="{{ old('titulo', $topico->titulo) }}" placeholder="Título da discussão">
                @error('titulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label" for="categoria">Categoria</label>
                    <select class="form-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria">
                        <option value="">Selecione</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected(old('categoria', $topico->categoria) === $categoria)>
                                {{ $categoria }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="system-info-grid mt-3">
                <div class="system-field">
                    <span>Autor</span>
                    <strong>{{ $topico->exists ? $topico->autor : auth()->user()->name }}</strong>
                </div>
                <div class="system-field">
                    <span>Data</span>
                    <strong>{{ $topico->exists ? $topico->data_criacao?->format('d/m/Y') : now('America/Sao_Paulo')->format('d/m/Y') }}</strong>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label" for="descricao">Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="8" placeholder="Conteúdo da discussão">{{ old('descricao', $topico->descricao) }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <aside class="form-panel side-panel">
            <label class="form-label" for="imagem">Imagem do tópico</label>
            <input class="form-control @error('imagem') is-invalid @enderror" type="file" id="imagem" name="imagem" accept="image/png,image/jpeg,image/webp">
            @error('imagem')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <p class="help-text mt-3">Opcional · JPG, PNG ou WEBP · até 2 MB</p>

            <div class="image-preview">
                @if($topico->imagem_url)
                    <img src="{{ $topico->imagem_url }}" alt="Imagem atual do tópico">
                @else
                    <span class="topic-placeholder large">
                        <i class="bi bi-image"></i>
                    </span>
                @endif
            </div>

            <div class="d-grid gap-2 mt-4">
                <button class="btn btn-primary-deblix" type="submit">
                    <i class="bi bi-send"></i> {{ $buttonLabel }}
                </button>
                <a class="btn btn-ghost" href="{{ $cancelUrl }}">Cancelar</a>
            </div>
        </aside>
    </div>
</div>

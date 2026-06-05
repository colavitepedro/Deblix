@extends('layouts.app')

@section('title', 'Editar tópico - Deblix')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="page-title">
            <h1>{{ $topico->titulo }}</h1>
        </div>

        <form method="POST" action="{{ route('topicos.update', $topico) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('topicos._form', [
                'topico' => $topico,
                'categorias' => $categorias,
                'buttonLabel' => 'Salvar alterações',
                'cancelUrl' => route('topicos.show', $topico),
            ])
        </form>
    </div>
</section>
@endsection

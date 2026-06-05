@extends('layouts.app')

@section('title', 'Novo tópico - Deblix')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="page-title">
            <h1>Comece uma discussão</h1>
        </div>

        <form method="POST" action="{{ route('topicos.store') }}" enctype="multipart/form-data">
            @csrf
            @include('topicos._form', [
                'topico' => $topico,
                'categorias' => $categorias,
                'buttonLabel' => 'Criar tópico',
                'cancelUrl' => route('topicos.index'),
            ])
        </form>
    </div>
</section>
@endsection

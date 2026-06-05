@extends('layouts.app')

@section('title', 'Entrar - Deblix')

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <div class="auth-head">
            <span class="brand-symbol">D</span>
            <h1>Entrar no Deblix</h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger app-alert" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                Verifique os dados informados.
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="email">E-mail</label>
                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email') }}" autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Senha</label>
                <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <label class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                <span class="form-check-label">Manter conectado</span>
            </label>

            <button class="btn btn-primary-deblix w-100" type="submit">Entrar</button>
        </form>

        <p class="auth-switch">
            Ainda não tem conta?
            <a href="{{ route('register') }}">Criar cadastro</a>
        </p>
    </div>
</section>
@endsection

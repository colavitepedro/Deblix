@extends('layouts.app')

@section('title', 'Cadastro - Deblix')

@section('content')
<section class="auth-section">
    <div class="auth-card">
        <div class="auth-head">
            <span class="brand-symbol">D</span>
            <h1>Criar conta</h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger app-alert" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                Verifique os dados informados.
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="name">Nome</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}" autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">E-mail</label>
                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email') }}">
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

            <div class="mb-4">
                <label class="form-label" for="password_confirmation">Confirmar senha</label>
                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <button class="btn btn-primary-deblix w-100" type="submit">Criar conta</button>
        </form>

        <p class="auth-switch">
            Já tem conta?
            <a href="{{ route('login') }}">Entrar</a>
        </p>
    </div>
</section>
@endsection

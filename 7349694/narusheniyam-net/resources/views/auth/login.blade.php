@extends('layouts.app')
@section('title','Вход - Нарушениям.Нет')
@section('content')
<section class="form-shell" aria-labelledby="login-title">
    <header class="form-header"><span class="eyebrow">Личный кабинет</span><h1 id="login-title">Авторизация</h1><p>Введите логин и пароль.</p></header>
    <form action="{{ route('login.attempt') }}" method="POST" class="form-card" novalidate data-validate>
        @csrf
        <div class="field"><label for="login">Логин</label><input id="login" name="login" value="{{ old('login') }}" required autocomplete="username"><small class="field-error">@error('login'){{ $message }}@enderror</small></div>
        <div class="field"><label for="password">Пароль</label><input id="password" name="password" type="password" required autocomplete="current-password"><small class="field-error">@error('password'){{ $message }}@enderror</small></div>
        <button class="btn btn-primary btn-block" type="submit">Авторизация</button>
        <p class="form-link">Нет профиля? <a href="{{ route('register') }}">Зарегистрироваться.</a></p>
    </form>
</section>
@endsection

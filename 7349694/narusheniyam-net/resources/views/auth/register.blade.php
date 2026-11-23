@extends('layouts.app')
@section('title','Регистрация - Нарушениям.Нет')
@section('content')
<section class="form-shell" aria-labelledby="register-title">
    <header class="form-header"><span class="eyebrow">Новый пользователь</span><h1 id="register-title">Регистрация</h1><p>Все поля обязательны.</p></header>
    <form action="{{ route('register.store') }}" method="POST" class="form-card" novalidate data-validate>
        @csrf
        <div class="field"><label for="fio">ФИО</label><input id="fio" name="fio" value="{{ old('fio') }}" required placeholder="Иванов Иван Иванович" data-rule="fio"><small class="field-hint">Ровно 3 слова кириллицей; допустим дефис.</small><small class="field-error">@error('fio'){{ $message }}@enderror</small></div>
        <div class="field"><label for="login">Логин</label><input id="login" name="login" value="{{ old('login') }}" required minlength="4" pattern="[A-Za-z0-9]+" placeholder="ivan2026"><small class="field-error">@error('login'){{ $message }}@enderror</small></div>
        <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="name@example.ru"><small class="field-error">@error('email'){{ $message }}@enderror</small></div>
        <div class="field"><label for="phone">Телефон</label><input id="phone" name="phone" value="{{ old('phone') }}" required inputmode="tel" placeholder="+7(999)123-45-67" data-phone-mask><small class="field-error">@error('phone'){{ $message }}@enderror</small></div>
        <div class="field"><label for="password">Пароль</label><input id="password" name="password" type="password" required minlength="6" autocomplete="new-password"><small class="field-hint">Не менее 6 символов.</small><small class="field-error">@error('password'){{ $message }}@enderror</small></div>
        <button class="btn btn-primary btn-block" type="submit">Зарегистрироваться</button>
        <p class="form-link">Есть профиль? <a href="{{ route('login') }}">Авторизоваться.</a></p>
    </form>
</section>
@endsection

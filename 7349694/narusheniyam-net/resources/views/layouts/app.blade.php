<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Нарушениям.Нет')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Нарушениям.Нет - на главную">
            <img src="{{ asset('images/logo.png') }}" alt="Логотип Нарушениям.Нет" class="brand-logo">
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Меню</button>
        <nav id="main-nav" class="main-nav" aria-label="Основная навигация">
            <a href="{{ route('home') }}" @class(['active'=>request()->routeIs('home')])>Главная</a>
            @guest
                <a href="{{ route('login') }}" @class(['active'=>request()->routeIs('login*')])>Вход</a>
                <a href="{{ route('register') }}" @class(['active'=>request()->routeIs('register*')])>Регистрация</a>
            @else
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.index') }}" @class(['active'=>request()->routeIs('admin.*')])>Администратор</a>
                @else
                    <a href="{{ route('reports.create') }}" @class(['active'=>request()->routeIs('reports.create')])>Подать заявление</a>
                    <a href="{{ route('reports.index') }}" @class(['active'=>request()->routeIs('reports.index')])>Мои заявки</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="nav-form">@csrf<button class="link-button" type="submit">Выход</button></form>
            @endguest
        </nav>
    </div>
</header>
<main class="site-main">
    <div class="container">
        @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-error" role="alert">Проверьте выделенные поля формы.</div>@endif
        @yield('content')
    </div>
</main>
<footer class="site-footer">
    <div class="container footer-inner">
        <p>Мы помогаем. На данный момент количество решённых проблем: <strong>{{ $resolvedCount }}</strong></p>
        <small>Нарушениям.Нет - городской сервис обращений</small>
    </div>
</footer>
</body>
</html>

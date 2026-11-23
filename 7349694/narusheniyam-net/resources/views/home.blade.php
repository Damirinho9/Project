@extends('layouts.app')
@section('title','Нарушениям.Нет - Главная')
@section('content')
<section class="hero" aria-labelledby="hero-title">
    <div class="hero-copy">
        <span class="eyebrow">Город начинается с нас</span>
        <h1 id="hero-title">Сообщайте о проблемах. Следите за их решением.</h1>
        <p>Единый сервис для заявлений о городских нарушениях - быстро, прозрачно и без лишней бюрократии.</p>
        @guest
            <div class="hero-actions"><a class="btn btn-primary" href="{{ route('register') }}">Создать профиль</a><a class="btn btn-secondary" href="{{ route('login') }}">Войти</a></div>
        @else
            @unless(auth()->user()->isAdmin())<a class="btn btn-primary" href="{{ route('reports.create') }}">Подать заявление</a>@endunless
        @endguest
    </div>
</section>
<section class="slider-section" aria-labelledby="slider-title">
    <div class="section-heading"><span class="eyebrow">Городская среда</span><h2 id="slider-title">Проблемы, которые можно решить вместе</h2></div>
    <div class="slider" data-slider aria-roledescription="carousel">
        <div class="slides">
            @foreach([1,2,3] as $slide)
                <figure class="slide {{ $slide === 1 ? 'is-active' : '' }}" data-slide aria-hidden="{{ $slide === 1 ? 'false' : 'true' }}">
                    <img src="{{ asset('images/slider-'.$slide.'.jpg') }}" alt="Городская проблема - слайд {{ $slide }}">
                </figure>
            @endforeach
        </div>
        <button class="slider-control prev" type="button" data-prev aria-label="Предыдущий слайд">← Назад</button>
        <button class="slider-control next" type="button" data-next aria-label="Следующий слайд">Вперёд →</button>
        <div class="slider-dots" aria-label="Навигация по слайдам"></div>
    </div>
</section>
<section class="feature-grid" aria-label="Преимущества сервиса">
    <article class="feature-card"><h3>Подайте заявление</h3><p>Опишите проблему, выберите категорию и удобный способ связи.</p></article>
    <article class="feature-card"><h3>Следите за статусом</h3><p>Новое, решено или отклонено - статус всегда виден в личном кабинете.</p></article>
    <article class="feature-card"><h3>Оставьте отзыв</h3><p>После решения проблемы оцените качество выполненных работ.</p></article>
</section>
@endsection

@extends('layouts.app')
@section('title','Подать заявление - Нарушениям.Нет')
@section('content')
<section class="form-shell wide" aria-labelledby="report-title">
    <header class="form-header"><span class="eyebrow">Новое обращение</span><h1 id="report-title">Подать заявление</h1><p>Расскажите о городской проблеме - заявление получит статус "Новое".</p></header>
    <form action="{{ route('reports.store') }}" method="POST" class="form-card" novalidate data-validate>
        @csrf
        <div class="field"><label for="title">Название проблемы</label><input id="title" name="title" value="{{ old('title') }}" required minlength="5" maxlength="180" placeholder="Например, глубокая яма у остановки"><small class="field-error">@error('title'){{ $message }}@enderror</small></div>
        <div class="field"><label for="category_id">Категория</label><select id="category_id" name="category_id" required><option value="">Выберите категорию</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->name }}</option>@endforeach</select><small class="field-error">@error('category_id'){{ $message }}@enderror</small></div>
        <div class="field"><label for="description">Описание</label><textarea id="description" name="description" rows="6" required minlength="10" maxlength="5000" placeholder="Укажите место и подробно опишите проблему">{{ old('description') }}</textarea><small class="field-error">@error('description'){{ $message }}@enderror</small></div>
        <div class="form-grid two">
            <div class="field"><label for="date_incident">Дата нарушения</label><input id="date_incident" name="date_incident" value="{{ old('date_incident',$today) }}" required inputmode="numeric" placeholder="ДД.ММ.ГГГГ" data-date-mask><small class="field-error">@error('date_incident'){{ $message }}@enderror</small></div>
            <fieldset class="field"><legend>Способ обратной связи</legend><label class="radio"><input type="radio" name="contact" value="email" @checked(old('contact')==='email') required> Email</label><label class="radio"><input type="radio" name="contact" value="sms" @checked(old('contact')==='sms')> SMS на телефон</label><small class="field-error">@error('contact'){{ $message }}@enderror</small></fieldset>
        </div>
        <button class="btn btn-primary" type="submit">Отправить</button>
    </form>
</section>
@endsection

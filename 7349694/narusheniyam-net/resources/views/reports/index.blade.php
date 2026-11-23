@extends('layouts.app')
@section('title','Мои заявки - Нарушениям.Нет')
@section('content')
<section aria-labelledby="reports-title">
    <div class="page-heading"><div><span class="eyebrow">Личный кабинет</span><h1 id="reports-title">Мои заявки</h1></div><a class="btn btn-primary" href="{{ route('reports.create') }}">Подать заявление</a></div>
    @forelse($reports as $report)
        <article class="report-card">
            <header class="report-card-head"><div><span class="meta">{{ $report->date_incident->format('d.m.Y') }}</span><h2>{{ $report->title }}</h2><p>{{ $report->category->name }}</p></div><span class="status status-{{ $report->status }}">{{ $report->status_label }}</span></header>
            <p class="report-description">{{ $report->description }}</p>
            @if($report->status === 'resolved')
                <form action="{{ route('reports.review',$report) }}" method="POST" class="review-form" data-validate>@csrf @method('PATCH')<label for="review-{{ $report->id }}">Отзыв о качестве работ</label><textarea id="review-{{ $report->id }}" name="review" rows="3" minlength="3" maxlength="2000" required>{{ old('review',$report->review) }}</textarea><button class="btn btn-secondary" type="submit">Сохранить отзыв</button></form>
            @endif
        </article>
    @empty
        <div class="empty-state"><h2>Заявок пока нет</h2><p>Сообщите о первой городской проблеме.</p><a class="btn btn-primary" href="{{ route('reports.create') }}">Подать заявление</a></div>
    @endforelse
    <div class="pagination-wrap">{{ $reports->links('partials.pagination') }}</div>
</section>
@endsection

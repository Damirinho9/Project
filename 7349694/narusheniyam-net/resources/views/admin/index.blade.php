@extends('layouts.app')
@section('title','Администратор - Нарушениям.Нет')
@section('content')
<section aria-labelledby="admin-title">
    <div class="page-heading"><div><span class="eyebrow">Панель управления</span><h1 id="admin-title">Все заявления</h1></div></div>
    <form method="GET" action="{{ route('admin.index') }}" class="filter-bar" aria-label="Фильтр по статусу">
        <label for="status">Статус</label><select id="status" name="status"><option value="">Все</option><option value="new" @selected($filter==='new')>Новое</option><option value="resolved" @selected($filter==='resolved')>Решено</option><option value="rejected" @selected($filter==='rejected')>Отклонено</option></select><button class="btn btn-secondary" type="submit">Фильтровать</button><a class="btn btn-ghost" href="{{ route('admin.index') }}">Сбросить</a>
    </form>
    <div class="table-wrap">
        <table class="admin-table"><thead><tr><th>ID</th><th>Статус</th><th>Категория</th><th>Проблема</th><th>Заявитель</th><th>Связь</th><th>Действие</th></tr></thead><tbody>
        @forelse($reports as $report)
            <tr><td>#{{ $report->id }}</td><td><span class="status status-{{ $report->status }}">{{ $report->status_label }}</span></td><td>{{ $report->category->name }}</td><td><strong>{{ $report->title }}</strong><p>{{ \Illuminate\Support\Str::limit($report->description,90) }}</p></td><td>{{ $report->user->fio }}</td><td>{{ $report->contact==='email' ? 'Email: '.$report->user->email : 'SMS: '.$report->user->phone }}</td><td>
                <form action="{{ route('admin.reports.status',$report) }}" method="POST" class="status-form" data-status-form>@csrf @method('PATCH')<label class="sr-only" for="status-{{ $report->id }}">Новый статус</label><select id="status-{{ $report->id }}" name="status"><option value="new" @selected($report->status==='new')>Новое</option><option value="resolved" @selected($report->status==='resolved')>Решено</option><option value="rejected" @selected($report->status==='rejected')>Отклонено</option></select><button class="btn btn-small" type="submit">Изменить</button></form>
            </td></tr>
        @empty<tr><td colspan="7">Заявления не найдены.</td></tr>@endforelse
        </tbody></table>
    </div>
    <div class="pagination-wrap">{{ $reports->links('partials.pagination') }}</div>
</section>
<dialog id="status-dialog" class="confirm-dialog"><form method="dialog"><h2>Подтверждение</h2><p>Вы действительно хотите изменить статус заявки?</p><div class="dialog-actions"><button value="cancel" class="btn btn-ghost">Отмена</button><button value="confirm" class="btn btn-danger">Изменить статус</button></div></form></dialog>
@endsection

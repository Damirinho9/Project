<?php
namespace App\Http\Controllers;
use App\Http\Requests\ReviewReportRequest;
use App\Http\Requests\StoreReportRequest;
use App\Models\Category;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class ReportController extends Controller
{
    public function index(): View
    {
        // user_id берётся только из текущей сессии - IDOR через параметр формы невозможен.
        $reports = auth()->user()->reports()->with('category')->latest()->paginate(8);
        return view('reports.index', compact('reports'));
    }
    public function create(): View
    {
        return view('reports.create', ['categories'=>Category::orderBy('name')->get(), 'today'=>now()->format('d.m.Y')]);
    }
    public function store(StoreReportRequest $request): RedirectResponse
    {
        $data = $request->validated();
        auth()->user()->reports()->create([
            'category_id'=>$data['category_id'],'title'=>$data['title'],'description'=>$data['description'],
            'date_incident'=>Carbon::createFromFormat('d.m.Y',$data['date_incident'])->format('Y-m-d'),
            'contact'=>$data['contact'],'status'=>'new',
        ]);
        return redirect()->route('reports.index')->with('success','Заявление успешно отправлено.');
    }
    public function review(ReviewReportRequest $request, Report $report): RedirectResponse
    {
        abort_unless($report->user_id === $request->user()->id, 403);
        abort_unless($report->status === 'resolved', 422, 'Отзыв доступен только для решённой заявки.');
        $report->update(['review'=>$request->validated('review')]);
        return back()->with('success','Спасибо! Отзыв сохранён.');
    }
}

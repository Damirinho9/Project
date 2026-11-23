<?php
namespace App\Http\Controllers;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->validate(['status'=>['nullable',Rule::in(['new','resolved','rejected'])]])['status'] ?? null;
        $query = Report::query()->with(['user','category'])->latest();
        if ($filter) $query->where('status',$filter);
        $reports = $query->paginate(10)->withQueryString();
        return view('admin.index', compact('reports','filter'));
    }
    public function updateStatus(UpdateStatusRequest $request, Report $report): RedirectResponse
    {
        $report->update(['status'=>$request->validated('status')]);
        return back()->with('success','Статус заявки изменён.');
    }
}

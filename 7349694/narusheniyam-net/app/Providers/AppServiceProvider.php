<?php
namespace App\Providers;
use App\Models\Report;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void
    {
        // Счётчик resolved доступен всем Blade-страницам и всегда берётся из БД.
        View::composer('*', function ($view): void {
            $view->with('resolvedCount', Schema::hasTable('reports') ? Report::where('status','resolved')->count() : 0);
        });
    }
}

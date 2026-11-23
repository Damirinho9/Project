<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureAdmin
{
    // Скрытие ссылки не является защитой: роль обязательно проверяется на сервере.
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isAdmin(), 403);
        return $next($request);
    }
}

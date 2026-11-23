<?php
namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
class AuthController extends Controller
{
    public function showLogin(): View { return view('auth.login'); }
    public function login(LoginRequest $request): RedirectResponse
    {
        // Auth::attempt сравнивает хеш пароля и предотвращает ручную SQL-конкатенацию.
        if (!Auth::attempt($request->only('login','password'))) {
            return back()->withErrors(['login'=>'Неверный логин или пароль'])->onlyInput('login');
        }
        $request->session()->regenerate();
        return redirect()->intended($request->user()->isAdmin() ? route('admin.index') : route('reports.index'));
    }
    public function showRegister(): View { return view('auth.register'); }
    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = User::create([
            'fio'=>$data['fio'],'login'=>$data['login'],'email'=>$data['email'],'phone'=>$data['phone'],
            'password'=>Hash::make($data['password']),'role'=>'user',
        ]);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('reports.create')->with('success','Регистрация завершена. Теперь можно подать заявление.');
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home')->with('success','Вы вышли из системы.');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IsUserAuth
{
    /**
     * Проверка, залогинен ли пользователь нажавший купить курс.
     * Инициируем сессию для редиректа на платежный гейт
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {             
        if ($request->session()->missing('seminar_id')) {
            session(['seminar_id' => $request->get('seminar_id'), 'toPaymentGate' => 'true']);
        }

        if(!Auth::user()) {
            return redirect()->route('register');
        }

        return $next($request);
    }
}

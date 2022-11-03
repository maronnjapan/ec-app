<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {

        // Auth::guard('admin')->check()はadminsテーブル内のユーザーデータと
        // 一致するものがあり、すでに認証されている場合はTrueを返す。
        // Auth::guard()は認証の際にどのデータベースモデルを使用するかを限定できる
        if(Auth::guard('admin')->check() && $request->routeIs('admin.*')){
            
            return redirect(RouteServiceProvider::ADMIN_HOME);
        }
        if(Auth::guard('owner')->check() && $request->routeIs('owner.*')){
            return redirect(RouteServiceProvider::OWNER_HOME);
        }
        if(Auth::guard('users')->check()){
            return redirect(RouteServiceProvider::HOME);
        }


        return $next($request);
    }
}

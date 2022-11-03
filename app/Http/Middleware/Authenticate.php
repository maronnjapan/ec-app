<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{

    // ログイン画面のURLに対応する名前付きルート
    private const ADMIN_LOGIN = 'admin.login';
    private const OWNER_LOGIN = 'owner.login';
    private const USER_LOGIN = 'login';
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        // リクエストがJSONの時は認証と関係ないので何もしない
        if ($request->expectsJson()) {
            return;
        }

        // リクエストしてきたURLのパスが/adminから始まっているとき
        // つまり管理者としてアクセスしたい場合
        if ($request->routeIs('admin.*')) {
            return route(self::ADMIN_LOGIN);
        }
        // リクエストしてきたURLのパスが/ownerから始まっているとき
        // つまりオーナーとしてアクセスしたい場合
        if ($request->routeIs('owner.*')) {
            return route(self::OWNER_LOGIN);
        }

        return route(self::USER_LOGIN);
    }
}

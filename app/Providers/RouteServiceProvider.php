<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     * 和訳:あなたのアプリのホーム画面のパスです
     * Typically, users are redirected here after authentication.
     * 和訳:一般的にはユーザー認証された後のリダイレクト先です。
     *
     * @var string
     */
    public const HOME = '/';
    public const OWNER_HOME = '/owner/dashboard';
    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        // この中でroutesフォルダ内の使用するファイルを決定する
        $this->routes(function () {
            // API用の設定のためマルチログイン実装では使用しない   
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // prefix('admin')を付けることで'/admin'のパスから始まるURLには等しくこちらの情報を使用する
            // name()によって等しく先頭にadmin.の名前付きルートを付与する
            Route::prefix('admin')
                ->name('admin.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));

            // prefix('owner')を付けることで'/owner'のパスから始まるURLには等しくこちらの情報を使用する
            // name()によって等しく先頭にowner.の名前付きルートを付与する
            Route::prefix('owner')
                ->name('owner.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/owner.php'));

            Route::prefix('/')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

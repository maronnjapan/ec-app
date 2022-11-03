<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('ImageUpload',function(){
            return new \App\Services\Image\ImageUploadService;
        });
        app()->bind('ImageDel',function(){
            return new \App\Services\Image\ImageDeleteService;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

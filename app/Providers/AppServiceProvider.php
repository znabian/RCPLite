<?php

namespace App\Providers;

use App\Http\Controllers\UserController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        if((!Storage::disk('public_html')->exists('Data/jobs.php')) || ( !Storage::disk('public_html')->exists('Data/ReportTitle.php')))
        {
           $uc=new UserController;
           $uc->createFiles();
        }
    }
}

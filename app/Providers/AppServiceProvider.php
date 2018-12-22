<?php

namespace App\Providers;

use App\ConfigHelper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use ConfigHelper;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

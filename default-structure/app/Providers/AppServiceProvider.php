<?php

namespace App\Providers;

use Illuminate\Session\SessionServiceProvider;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(SessionServiceProvider::class);
        }
    }
    /**
 * Bootstrap any application services.
 *
 * @return void
 */
public function boot()
{
    Schema::defaultStringLength(191);
}
}

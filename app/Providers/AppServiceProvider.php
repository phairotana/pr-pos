<?php

namespace App\Providers;

use App\Micro\NumberFormatMicro;
use App\Models\Adjustment;
use App\Observers\AdjustmentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Str::mixin(new NumberFormatMicro);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Adjustment::observe(AdjustmentObserver::class);
        $this->app->bind(
            \Backpack\PermissionManager\app\Http\Controllers\UserCrudController::class, //this is package controller
            \App\Http\Controllers\Admin\UserCrudController::class //this should be your own controller
        );

    }
}

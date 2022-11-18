<?php

namespace App\Providers;

use App\Services\Couriers\TheCourierGuy\TheCourierGuyService;
use Illuminate\Support\ServiceProvider;

class TheCourierGuyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TheCourierGuyService::class, function($app) {
            return new TheCourierGuyService(
                host: config("thecourierguy.api.host"),
                accessId: config("thecourierguy.api.access_id"),
                accessSecret: config("thecourierguy.api.access_secret"),
            );
        });


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

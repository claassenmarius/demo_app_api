<?php

namespace App\Providers;

use App\Services\Couriers\Skynet\SkynetService;
use Illuminate\Support\ServiceProvider;

class SkynetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SkynetService::class, function($app) {
            return new SkynetService(
                host: config("skynet.api.host"),
                username: config("skynet.api.username"),
                password: config("skynet.api.password"),
                systemId: config("skynet.api.system_id"),
                accountNumber: config("skynet.api.account_number"),
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

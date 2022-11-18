<?php

namespace App\Providers;

use App\Http\Controllers\ShipmentQuoteController;
use App\Services\Couriers\Contracts\CourierServiceContract;
use App\Services\Couriers\Skynet\SkynetService;
use App\Services\Couriers\TheCourierGuy\TheCourierGuyService;
use Illuminate\Support\ServiceProvider;

class CourierServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(ShipmentQuoteController::class)
            ->needs(CourierServiceContract::class)
            ->give(function ($app) {
                return [
                    $app->make(SkynetService::class),
                    $app->make(TheCourierGuyService::class),
                ];
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

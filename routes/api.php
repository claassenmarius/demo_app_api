<?php

use App\Http\Controllers\ShipmentQuoteController;
use App\Services\Couriers\DataObjects\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/shipments', function (Request $request) {
    return \Illuminate\Support\Facades\Auth::user()->shipments;
});

Route::middleware('auth:sanctum')->post('/shipment_quote', ShipmentQuoteController::class);

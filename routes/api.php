<?php

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

Route::group(['prefix' => 'foodfleet', 'as' => 'api.foodfleet', "middleware" => ['auth:api']], function () {
    Route::apiResource('financial-reports', 'Foodfleet\FinancialReports');
    Route::apiResource('financial-modifiers', 'Foodfleet\FinancialModifiers');
});

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

    Route::get('categories', 'Foodfleet\Categories@index');
    Route::get('customers', 'Foodfleet\Customers@index');
    Route::get('events', 'Foodfleet\Events@index');
    Route::get('event-tags', 'Foodfleet\EventTags@index');
    Route::get('stores', 'Foodfleet\Stores@index');
    Route::get('items', 'Foodfleet\Items@index');
    Route::get('locations', 'Foodfleet\Locations@index');
    Route::get('payments', 'Foodfleet\Payments@index');
    Route::get('staffs', 'Foodfleet\Staffs@index');
    Route::get('transactions', 'Foodfleet\Transactions@index');
    Route::get('payment-types', 'Foodfleet\PaymentTypes@index');
    Route::get('devices', 'Foodfleet\Devices@index');
    Route::get('financial-summary', 'Foodfleet\FinancialSummary@index');
    Route::post('squares', 'Foodfleet\Squares@store');
});

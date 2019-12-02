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

    Route::get('documents/new', 'Foodfleet\Documents@showNewRecommendation');
    Route::apiResource('documents', 'Foodfleet\Documents');
    Route::apiResource('document-statuses', 'Foodfleet\DocumentStatuses');
    Route::apiResource('document-types', 'Foodfleet\DocumentTypes');
    Route::apiResource('tmp-media', 'Foodfleet\TmpMedia');

    Route::get('events/new', 'Foodfleet\Events@showNewRecommendation');
    Route::apiResource('events', 'Foodfleet\Events');
    Route::get('event-tags', 'Foodfleet\EventTags@index');
    Route::get('event-statuses', 'Foodfleet\EventStatuses@index');

    Route::apiResource('stores', 'Foodfleet\Stores');
    Route::get('store-tags', 'Foodfleet\StoreTags@index');
    Route::get('store-statuses', 'Foodfleet\StoreStatuses@index');

    Route::get('menus/new', 'Foodfleet\Menus@showNewRecommendation');
    Route::apiResource('menus', 'Foodfleet\Menus');

    Route::get('categories', 'Foodfleet\Categories@index');
    Route::get('customers', 'Foodfleet\Customers@index');
    Route::get('items', 'Foodfleet\Items@index');
    Route::get('locations', 'Foodfleet\Locations@index');
    Route::get('payments', 'Foodfleet\Payments@index');
    Route::get('staffs', 'Foodfleet\Staffs@index');
    Route::get('transactions', 'Foodfleet\Transactions@index');
    Route::get('transactions/{uuid}', 'Foodfleet\Transactions@show');
    Route::get('payment-types', 'Foodfleet\PaymentTypes@index');
    Route::get('devices', 'Foodfleet\Devices@index');
    Route::get('financial-summary', 'Foodfleet\FinancialSummary@index');
    Route::post('squares', 'Foodfleet\Squares@store');
    Route::get('venues', 'Foodfleet\Venues@index');
});

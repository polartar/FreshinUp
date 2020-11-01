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
    Route::apiResource('document-statuses', 'Foodfleet\DocumentStatuses');
    Route::apiResource('document-types', 'Foodfleet\DocumentTypes');
    Route::apiResource('document/template/statuses', 'Foodfleet\Document\Template\Statuses')
        ->only('index');
    Route::apiResource('document/template/types', 'Foodfleet\Document\Template\Types')
        ->only('index');
    Route::apiResource('document/templates', 'Foodfleet\Document\Template\Templates')
        ->only('index', 'show', 'store', 'update', 'destroy');
    Route::apiResource('documents', 'Foodfleet\Documents');
    Route::apiResource('tmp-media', 'Foodfleet\TmpMedia');
    Route::apiResource('company-owners', 'Foodfleet\CompanyOwners');
    Route::get('categories', 'Foodfleet\Categories@index');
    Route::get('customers', 'Foodfleet\Customers@index');

    Route::get('events/new', 'Foodfleet\Events\Events@showNewRecommendation');
    Route::get('events/{event}/stores', 'Foodfleet\Events\Store@index');
    Route::get('event-summary/{uuid}', 'Foodfleet\Events\Events@summary');
    Route::apiResource('events', 'Foodfleet\Events\Events');
    Route::get('event-tags', 'Foodfleet\EventTags@index');
    Route::get('event-statuses', 'Foodfleet\EventStatuses@index');
    Route::get('event/types', 'Foodfleet\EventType@index');
    Route::get('event/status/histories', 'Foodfleet\EventHistory@index');

    Route::apiResource('stores', 'Foodfleet\Store');
    Route::get('stores/new', 'Foodfleet\Store@showNewRecommendation');
    Route::get('store-statuses', 'Foodfleet\StoreStatuses@index');
    Route::get('store-tags', 'Foodfleet\StoreTags@index');
    Route::get('store-summary/{uuid}', 'Foodfleet\Store@summary');
    Route::get('store-service-summary/{uuid}', 'Foodfleet\Store@serviceSummary');
    Route::get('store/types', 'Foodfleet\StoreType@index');
    Route::apiResource('store/areas', 'Foodfleet\StoreArea')->only('store', 'index', 'destroy');


    Route::get('event-menu-items/new', 'Foodfleet\EventMenuItems@showNewRecommendation');
    Route::apiResource('event-menu-items', 'Foodfleet\EventMenuItems');
    Route::get('menus/new', 'Foodfleet\Menus@showNewRecommendation');
    Route::apiResource('menus', 'Foodfleet\Menus');
    Route::get('categories', 'Foodfleet\Categories@index');
    Route::get('customers', 'Foodfleet\Customers@index');
    Route::get('items', 'Foodfleet\Items@index');


    Route::get('location/categories', 'Foodfleet\LocationCategory@index');
    Route::apiResource('locations', 'Foodfleet\Locations')->only('index', 'store', 'destroy');


    Route::get('payments', 'Foodfleet\Payments@index');
    Route::get('staffs', 'Foodfleet\Staffs@index');
    Route::get('transactions', 'Foodfleet\Transactions@index');
    Route::get('transactions/{uuid}', 'Foodfleet\Transactions@show');
    Route::get('payment-types', 'Foodfleet\PaymentTypes@index');
    Route::get('devices', 'Foodfleet\Devices@index');
    Route::get('financial-summary', 'Foodfleet\FinancialSummary@index');
    Route::get('companies/{company}/members', 'Foodfleet\Companies\CompanyMembers@index');

    Route::apiResource('venues', 'Foodfleet\Venues');
    Route::get('venue/statuses', 'Foodfleet\VenueStatuses@index');

    Route::get('messages/new', 'Foodfleet\Messages@showNewRecommendation');
    Route::get('messages', 'Foodfleet\Messages@index');
    Route::post('messages', 'Foodfleet\Messages@store');

    Route::apiResource('menu-items', 'Foodfleet\MenuItems');


    Route::get('companies/{company}/square-locations', 'Foodfleet\Square@locations');
    Route::post('/squares/authorize', 'Foodfleet\Square@authorizeApp')
        ->name('square.authorize');
});

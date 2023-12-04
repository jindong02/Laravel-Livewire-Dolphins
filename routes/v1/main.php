<?php

use App\Enums\Permission;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', 'UserController')->except('destroy')->names('api.users');
Route::post('users/{id}/reset-password', 'UserController@resetPassword');

Route::prefix('transactions')->group(function () {

    Route::middleware(['permission:' . Permission::DEPARTMENT_APPROVAL])->group(function () {
        Route::get('item-requests/approval/department', 'ItemRequestApprovalController@deptIndex');
        Route::post('item-requests/approval/department', 'ItemRequestApprovalController@deptValidation');
        Route::post('item-requests/approval/department/{item_request}', 'ItemRequestApprovalController@deptItemValidation');
    });

    Route::middleware(['permission:' . Permission::ITEM_REQUEST])->group(function () {
        Route::prefix('item-requests/{item_request}')->group(function () {
            Route::post('items', 'ItemRequestDetailController@store');
            Route::put('items/{item}', 'ItemRequestDetailController@update');
            Route::delete('items/{item}', 'ItemRequestDetailController@destroy');
        });
        Route::post('item-requests', 'ItemRequestController@store');
        Route::put('item-requests/{item_requests}', 'ItemRequestController@update');
        Route::delete('item-requests/{item_requests}', 'ItemRequestController@destroy');
        Route::post('item-requests/{item_requests}/submit', 'ItemRequestController@submit');
    });


    /**
     * Allowed for all
     */
    Route::prefix('item-requests/{item_request}')->group(function () {
        Route::get('items', 'ItemRequestDetailController@index');
        Route::get('items/{item}', 'ItemRequestDetailController@show');
    });

    Route::get('item-requests', 'ItemRequestController@index');
    Route::get('item-requests/{item_requests}', 'ItemRequestController@show');

    Route::middleware(['permission:' . Permission::PURCHASE_REQUEST])->group(function () {
        Route::get('purchase-request', 'PurchaseRequestController@index');
        Route::get('purchase-request/{purchase_request}', 'PurchaseRequestController@show');
        Route::post('purchase-request/memo', 'PurchaseRequestController@addMemo');
    });

});

Route::prefix('common')->group(function () {
    Route::get('items', 'CommonController@items');
    Route::get('modes', 'CommonController@modes');
    Route::get('fund-sources', 'CommonController@fundSources');
    Route::get('supply-types', 'CommonController@supplyTypes');
    Route::get('departments', 'CommonController@departments');
});

Route::post('download/{media}', 'MediaController@getUrl')->name('media.download.url');

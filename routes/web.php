<?php

use App\Enums\Permission;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidderController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PlotModuleController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\RequestItemsApprovalController;
use App\Http\Controllers\RequestItemsController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseRequestMinuteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/', 'pages.home')->name('home');

    Route::get('download/{media}', [MediaController::class, 'download'])->name('media.download');

    Route::middleware(['permission:' . Permission::USERS])->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware(['permission:' . Permission::ROLES])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    Route::name('settings.')->prefix('settings')->group(function () {
        Route::resource('departments', DepartmentController::class)->middleware(['permission:' . Permission::DEPARTMENTS]);
        Route::resource('bidders', BidderController::class)->middleware(['permission:' . Permission::BIDDERS]);
    });

    // ----------------- Routes for Module Groups --------------------
    Route::name('modulegroup.')->prefix('modulegroup')->group(function() {
        Route::resource('plotmodule', PlotModuleController::class);
    });


    Route::name('approvals.')->prefix('request-item/approval')->group(function() {
        Route::middleware(['permission:' . Permission::DEPARTMENT_APPROVAL])->group(function () {
            Route::get('department', [RequestItemsApprovalController::class, 'deptIndex'])->name('department.index');
            Route::post('department', [RequestItemsApprovalController::class, 'deptValidate'])->name('department.validate');
            Route::get('department/{request_item}', [RequestItemsApprovalController::class, 'deptItemRequest'])->name('department.show');
        });

        Route::middleware(['permission:' . Permission::BUDGET_APPROVAL])->group(function () {
            Route::get('budget', [RequestItemsApprovalController::class, 'budgetIndex'])->name('budget.index');
            Route::post('budget', [RequestItemsApprovalController::class, 'budgetValidate'])->name('budget.validate');
            Route::get('budget/{request_item}', [RequestItemsApprovalController::class, 'budgetItemRequest'])->name('budget.show');
            Route::post('budget/department', [RequestItemsApprovalController::class, 'budgetValidatePerDepartment'])->name('budget.department.validate');
            Route::get('budget/department/{department_id}/{view}', [RequestItemsApprovalController::class, 'budgetViewPerDeptType'])->name('budget.department.index');
        });

        Route::middleware(['permission:' . Permission::BAC_1_APPROVAL])->group(function () {
            Route::get('bac-1', [RequestItemsApprovalController::class, 'bac1Index'])->name('bac-1.index');
            Route::post('bac-1', [RequestItemsApprovalController::class, 'bac1Validate'])->name('bac-1.validate');
            Route::get('bac-1/{request_item}', [RequestItemsApprovalController::class, 'bac1ItemRequest'])->name('bac-1.show');
        });

        Route::middleware(['permission:' . Permission::BAC_2_APPROVAL])->group(function () {
            Route::get('bac-2', [RequestItemsApprovalController::class, 'bac2Index'])->name('bac-2.index');
            Route::post('bac-2', [RequestItemsApprovalController::class, 'bac2CreatePrFromList'])->name('bac-2.store-pr.list');
            Route::get('bac-2/{request_item}', [RequestItemsApprovalController::class, 'bac2ItemRequest'])->name('bac-2.show');
        });

    });

    Route::middleware(['permission:' . Permission::ITEM_REQUEST])->group(function () {
        Route::resource('request-items', RequestItemsController::class);
    });

    Route::middleware(['permission:' . Permission::PURCHASE_REQUEST])->group(function () {
        Route::prefix('purchase-requests/{purchase_request}')->group(function() {
            Route::resource('minutes', PurchaseRequestMinuteController::class);
        });
        Route::resource('purchase-requests', PurchaseRequestController::class);
    });

});

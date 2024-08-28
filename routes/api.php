<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stock\Purchases\FilterSections;
use App\Http\Controllers\Stock\Exchange\ExchangeController;
use App\Http\Controllers\Stock\Purchases\PurchasesController;
use App\Http\Controllers\Stock\Exchange\MaterialBalanceController;

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

/*
        * @routes('/purchases)
        */

Route::group(
    [
        'prefix' => 'purchases',
        'as' => 'purchases.',
    ],
    function () {
        Route::get('/', [PurchasesController::class, 'index'])->name('index');
        Route::post('/', [PurchasesController::class, 'store'])->name('store');
        Route::get('/{purchase}', [PurchasesController::class, 'show'])->name('show');
        Route::post('/{purchase}', [PurchasesController::class, 'update'])->name('update');
        Route::delete('/{purchase}', [PurchasesController::class, 'destroy'])->name('destroy');
        Route::get('/sections/filter/{branch}', FilterSections::class)->name('sections.filter');
    }
);

/*
        * @routes('/exchange)
        */
Route::group(
    [
        'prefix' => 'exchange',
        'as' => 'exchange.',
    ],
    function () {
        Route::get('/', [ExchangeController::class, 'index'])->name('index');
        Route::post('/', [ExchangeController::class, 'store'])->name('store');
        Route::get('/materials/filter/{store}', MaterialBalanceController::class)->name('material.store.filter');
    }
);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stock\Purchases\FilterSections;
use App\Http\Controllers\Stock\Exchange\ExchangeController;
use App\Http\Controllers\Stock\Purchases\PurchasesController;
use App\Http\Controllers\Stock\Exchange\MaterialBalanceController;
use App\Http\Controllers\Stock\MaterialTransfer\MaterialTransferController;
use App\Http\Controllers\Stock\MaterialTransfer\SectionMaterialBalanceController;

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

        Route::controller(ExchangeController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{exchange}', 'show')->name('show');
            Route::post('/{exchange}', 'update')->name('update');
            Route::delete('/{exchange}', 'destroy')->name('destroy');
        });
        Route::get('/materials/filter/{store}', MaterialBalanceController::class)->name('material.store.filter');
    }
);


/*
        * @routes('/exchange)
        */
Route::group(
    [
        'prefix' => '/material/transfer',
        'as' => 'material.transfer.',
    ],
    function () {
        Route::controller(MaterialTransferController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{transfer}', 'show')->name('show');
            Route::post('/{transfer}', 'update')->name('update');
            Route::delete('/{transfer}', 'destroy')->name('destroy');
        });
        Route::get('/filter/{section}', SectionMaterialBalanceController::class)->name('section.filter');
    }
);

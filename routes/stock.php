<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stock\OrdersControllers;
use App\Http\Controllers\Stock\MaterialOperations;
use App\Http\Controllers\Stock\materialManufacturing;
use App\Http\Controllers\Stock\OpenBalanceController;
use App\Http\Controllers\Stock\InDirectCostController;
use App\Http\Controllers\Stock\Stores\StoreController;
use App\Http\Controllers\Stock\Purchases\FilterBalance;
use App\Http\Controllers\Stock\ComponentItemsController;
use App\Http\Controllers\Stock\Purchases\FilterSections;
use App\Http\Controllers\Stock\Groups\SubGroupController;
use App\Http\Controllers\Stock\Groups\MainGroupController;
use App\Http\Controllers\Stock\Items\FilterItemController;
use App\Http\Controllers\Stock\OpenBalanceDailyController;
use App\Http\Controllers\Stock\Sections\SectionController;
use App\Http\Controllers\Stock\Exchange\ExchangeController;
use App\Http\Controllers\Stock\Material\MaterialController;
use App\Http\Controllers\StockReports\HalkReportsController;
use App\Http\Controllers\Stock\Purchases\PurchasesController;
use App\Http\Controllers\Stock\Suppliers\SuppliersController;
use App\Http\Controllers\StockReports\ItemsPricingController;
use App\Http\Controllers\StockReports\StockReportsController;
use App\Http\Controllers\Stock\ComponentDetailsItemController;
use App\Http\Controllers\StockReports\TransferReportController;
use App\Http\Controllers\Stock\Material\FilterSectionController;
use App\Http\Controllers\StockReports\CardItemReportsController;
use App\Http\Controllers\StockReports\ExchangesReportController;
use App\Http\Controllers\StockReports\HalkItemReportsController;
use App\Http\Controllers\StockReports\PurchasesReportController;
use App\Http\Controllers\Stock\Material\FilterMaterialController;
use App\Http\Controllers\Stock\Material\FilterSubGroupController;
use App\Http\Controllers\Stock\Material\MaterialRecipeController;
use App\Http\Controllers\StockReports\SuppliersReportsController;
use App\Http\Controllers\Stock\Exchange\MaterialBalanceController;
use App\Http\Controllers\StockReports\BackStoresReportsController;
use App\Http\Controllers\StockReports\OperationsReportsController;
use App\Http\Controllers\Stock\Items\FilterItemComponentController;
use App\Http\Controllers\Stock\MaterialHalk\MaterialHalkController;
use App\Http\Controllers\StockReports\BackSuppliersReportsController;
use App\Http\Controllers\StockReports\ManufacturingReportsController;
use App\Http\Controllers\Stock\Material\FilterMaterialBranchController;
use App\Http\Controllers\Stock\Material\FilterMaterialRecipeController;
use App\Http\Controllers\Stock\ItemsDetails\FilterItemsDetailsController;
use App\Http\Controllers\Stock\StoreRefund\MaterialStoreRefundController;
use App\Http\Controllers\Stock\Material\FilterNotRecipeMaterialController;
use App\Http\Controllers\Stock\MaterialTransfer\MaterialTransferController;
use App\Http\Controllers\Stock\MaterialHalk\Item\MaterialHalkItemController;
use App\Http\Controllers\Stock\MaterialHalk\Item\FilterSectionItemsController;
use App\Http\Controllers\Stock\SupplierRefund\MaterialSupplierRefundController;
use App\Http\Controllers\Stock\MaterialTransfer\SectionMaterialBalanceController;


Route::group(
    [
        'middleware' => [
            'auth',
        ],
        'prefix' => 'stock',
        'as' => 'stock.'
    ],
    function () {
        /*
      * @route('stores)
      */
        Route::resource('stores', StoreController::class);

        /*
      * @route('sections)
      */
        Route::resource('sections', SectionController::class);
        Route::post('/sections/groups', [SectionController::class, 'getSectionGroups'])->name('sections.groups');

        /*
      * @route('suppliers)
      */
        Route::resource('suppliers', SuppliersController::class);

        /*
      * @route('main/groups)
      */
        Route::group(
            [
                'prefix' => 'main',
                'as' => 'main.'
            ],
            function () {
                Route::resource('groups', MainGroupController::class, [
                    'parameters' => [
                        'groups' => 'stockGroup'
                    ],
                ]);
            }
        );

        /*
      * @route('sub/groups)
      */

        Route::group(
            [
                'prefix' => 'sub',
                'as' => 'sub.',
                'controller' => SubGroupController::class,
            ],
            function () {
                Route::get('groups', 'index')->name('groups.index');
                Route::get('groups/{stockGroup}/filter', 'filter')->name('groups.filter');
                Route::post('groups', 'store')->name('groups.store');
                Route::get('groups/{stockGroup}', 'show')->name('groups.show');
                Route::put('groups/{stockGroup}', 'update')->name('groups.update');
                Route::delete('groups/{stockGroup}', 'destroy')->name('groups.destroy');
            }
        );

        /*
      * @route('material')
      */
        Route::resource('materials', MaterialController::class);
        Route::group([
            'prefix' => 'material',
            'as' => 'material.',
        ], function () {
            Route::get('groups/{stockGroup}/filter', FilterSubGroupController::class)->name('groups.filter');
            Route::get('sections/{branch}/filter', FilterSectionController::class)->name('sections.filter');
            Route::get('filter/{branch}', FilterMaterialController::class)->name('branchfilter');
            Route::get('{branch}/filter', FilterNotRecipeMaterialController::class)->name('filter');

            // material recipe routes
            Route::resource('recipe', MaterialRecipeController::class, [
                'parameters' => [
                    'recipe' => 'materialRecipe',
                ],

            ]);
            Route::post('/recipe/filter', [MaterialRecipeController::class, 'filter'])
                ->name('recipe.filter');
            Route::post('/recipe/repeat', [MaterialRecipeController::class, 'repeat'])
                ->name('recipe.repeat');
            Route::get('/recipe/{branch}/filter', FilterMaterialBranchController::class);
            Route::get('/recipe/filter/{material}', FilterMaterialRecipeController::class);
        });

        /*
      * @route('item/components')
      */
        Route::group(
            [
                'prefix' => 'items',
                'as' => 'items.'
            ],
            function () {
                Route::get('/{branch}/filter', FilterItemController::class);
                Route::get('/components/{branch}/filter', FilterItemComponentController::class);
                Route::group([
                    'prefix' => 'details/component',
                ], function () {
                    Route::get('/{branch}/filter', FilterItemsDetailsController::class);
                });
            }
        );

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
                Route::get('/{material}/filter', FilterBalance::class)->name('materials.filter');
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
        * @routes('/material/transfer)
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
                    Route::post('/{transfer} ', 'update')->name('update');
                    Route::delete('/{transfer}', 'destroy')->name('destroy');
                });
                Route::get('/filter/{section}', SectionMaterialBalanceController::class)->name('section.filter');
            }
        );

        /*
        * @routes('/material/halk)
        */
        Route::group(
            [
                'prefix' => '/material/halk',
                'as' => 'material.halk.',
            ],
            function () {
                Route::controller(MaterialHalkController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{halk}', 'show')->name('show');
                    Route::post('/{halk} ', 'update')->name('update');
                    Route::delete('/{halk}', 'destroy')->name('destroy');
                });
            }
        );

        /*
        * @routes(/material/halks/item)
        */
        Route::group(
            [
                'prefix' => '/material/halks',
                'as' => 'material.halks.',
            ],
            function () {
                Route::resource('item', MaterialHalkItemController::class);
                Route::get('item/{branch}/filter', FilterSectionItemsController::class);
            }
        );


        /*
        * @routes('material/supplier/refund)
        */

        Route::group(
            [
                'prefix' => '/material/supplier/refund',
                'as' => 'material.supplier.refund.',
            ],
            function () {
                Route::get('/', [MaterialSupplierRefundController::class, 'index'])->name('index');
                Route::post('/', [MaterialSupplierRefundController::class, 'store'])->name('store');
                Route::get('/{refund}', [MaterialSupplierRefundController::class, 'show'])->name('show');
                Route::post('/{refund}', [MaterialSupplierRefundController::class, 'update'])->name('update');
                Route::delete('/{refund}', [MaterialSupplierRefundController::class, 'destroy'])->name('destroy');
            }
        );


        /*
        * @routes('material/store/refund)
        */

        Route::group(
            [
                'prefix' => '/material/store/refund',
                'as' => 'material.store.refund.',
            ],
            function () {
                Route::get('/', [MaterialStoreRefundController::class, 'index'])->name('index');
                Route::post('/', [MaterialStoreRefundController::class, 'store'])->name('save');
                Route::get('/{refund}', [MaterialStoreRefundController::class, 'show'])->name('show');
                Route::post('/{refund}', [MaterialStoreRefundController::class, 'update'])->name('update');
                Route::delete('/{refund}', [MaterialStoreRefundController::class, 'destroy'])->name('destroy');
                Route::get('/filter/{section}', SectionMaterialBalanceController::class)->name('section.balance.filter');
            }
            
        );
    }
);


################################## Component Items ###################################
Route::group(['prefix' => 'stock', 'controller' => ComponentItemsController::class], function () {
    Route::get('components_items', 'index')->name('view_components_items');
    Route::post('components_items_get_material', 'get_material')->name('components_items_get_material');
    Route::post('components_items_get_material_in_item', 'get_material_in_item')->name('components_items_get_material_in_item');
    Route::post('saveComponent', 'saveComponent')->name('saveComponent');
    Route::post('deleteComponent', 'deleteComponent')->name('deleteComponent');
    Route::post('transfer_material', 'transfer_material')->name('transferMaterial');
    // This is Routs Reports in page
    Route::post('printComponents', 'printComponents')->name('printComponents');
    Route::post('printItems', 'printItems')->name('printItems');
    Route::post('printComponent', 'printComponent')->name('printComponent');
    Route::get('componentWithoutItems/{branch}/filter', 'componentWithoutItems')->name('componentWithoutItems');
});

#################################### Component details item ############################################
Route::group(['prefix' => 'stock', 'controller' => ComponentDetailsItemController::class], function () {
    Route::get('componentDetailsItem', 'index')->name('componentDetailsItem');
    Route::post('getItemDetails', 'getItemDetails')->name('getItemDetails');
    Route::post('getDetails', 'getDetails')->name('getDetails');
    Route::post('saveDetailsComponent', 'saveDetailsComponent')->name('saveDetailsComponent');
    Route::post('deleteDetailsRecipe', 'deleteDetailsRecipe')->name('deleteDetailsRecipe');
    Route::post('getMaterialsInDetails', 'getMaterialsInDetails')->name('getMaterialsInDetails');
    Route::post('transfierMaterialDetails', 'transfierMaterialDetails')->name('transfierMaterialDetails');
    // This is Routs Reports in page
    // Route::post('DetailsWithoutMaterials', 'DetailsWithoutMaterials')->name('DetailsWithoutMaterials');
    Route::post('printDetails', 'printDetails')->name('printDetails');
});



Route::group(['prefix' => 'stock', 'controller' => MaterialOperations::class], function () {
    Route::get('materialOperations', 'index')->name('materialOperations');
    Route::post('getMaterialsOperations', 'getMaterialsOperations')->name('getMaterialsOperations');
    Route::post('getMaterialsComponents', 'getMaterialsComponents')->name('getMaterialsComponents');
    Route::post('saveOperations', 'saveOperations')->name('saveOperations');
    Route::post('getOperationViaOrder', 'getOperationViaOrder')->name('getOperationViaOrder');
    Route::post('getDetailsMaterialsCost', 'getSectionCost')->name('getDetailsMaterialsCost');
});

Route::group(['prefix' => 'stock', 'controller' => materialManufacturing::class], function () {
    Route::get('materialManufacturing', 'index')->name('materialManufacturing');
    Route::post('getManufacturingViaOrder', 'getOrders')->name('getManufacturingViaOrder');
    Route::post('saveManufacturing', 'store')->name('saveManufacturing');
    Route::post('getMaterialsManufacturing', 'getMaterials')->name('getMaterialsManufacturing');
});

Route::group(['prefix' => 'reports', 'controller' => StockReportsController::class, 'as' => 'reports.'], function () {
    Route::get('store-balance', 'store_balance')->name('store_balance');
});

Route::group(['prefix' => 'OpenBalanceDaily', 'controller' => OpenBalanceDailyController::class, 'as' => 'inventoryDaily.'], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/getMaterials', 'getMaterials')->name('getMaterials');
    Route::post('/storeInventory', 'store')->name('storeInventory');
});

Route::group(['prefix' => 'OpenBalance', 'controller' => OpenBalanceController::class, 'as' => 'inventory.'], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/getMaterials', 'getMaterials')->name('getMaterials');
    Route::post('/storeInventory', 'store')->name('storeInventory');
});

Route::group(['prefix' => 'InDirectCost', 'controller' => InDirectCostController::class, 'as' => 'inDirectCost.'], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/save', 'save')->name('save');
    Route::post('/update', 'update')->name('update');
    Route::post('/destroy', 'destroy')->name('destroy');
    Route::post('/saveInDirectValue', 'saveInDirectValue')->name('saveInDirectValue');
    Route::post('/deleteInDirectValue', 'deleteInDirectValue')->name('deleteInDirectValue');
});

Route::group(['prefix' => 'stock/orders', 'controller' => OrdersControllers::class, 'as' => 'stock.orders.'], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/save', 'save')->name('save');
    Route::post('/update', 'update')->name('update');
    Route::post('/getData', 'getData')->name('getData');
    Route::post('/destroy', 'destroy')->name('destroy');
});

Route::group(['prefix' => 'reports/', 'middleware' => 'auth', 'as' => 'reports.'], function () {
    // Item Pricing Reports Controller
    Route::group(['prefix' => 'items-pricing', 'controller' => ItemsPricingController::class, 'as' => 'items-pricing.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/getGroups', 'getGroups')->name('getGroups');
        Route::post('/getSubGroups', 'getSubGroups')->name('getSubGroups');
        Route::post('/getItems', 'getItems')->name('getItems');
    });
    // Exchange Reports Controller
    Route::group(['prefix' => 'exchange', 'controller' => ExchangesReportController::class, 'as' => 'exchange.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });
    // Exchange Reports Controller
    Route::group(['prefix' => 'transfer', 'controller' => TransferReportController::class, 'as' => 'transfer.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });
    // PurchasesReportController Reports Controller
    Route::group(['prefix' => 'purchases', 'controller' => PurchasesReportController::class, 'as' => 'purchases.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });

    // BackStoresReportsController Reports Controller
    Route::group(['prefix' => 'backStores', 'controller' => BackStoresReportsController::class, 'as' => 'backStores.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });

    // BackSuppliersReportsController Reports Controller
    Route::group(['prefix' => 'backSuppliers', 'controller' => BackSuppliersReportsController::class, 'as' => 'backSuppliers.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });

    // CardItemReportsController Reports Controller
    Route::group(['prefix' => 'cardItem', 'controller' => CardItemReportsController::class, 'as' => 'cardItem.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });

    // HalkItemReportsController Reports Controller
    Route::group(['prefix' => 'halkItem', 'controller' => HalkItemReportsController::class, 'as' => 'halkItem.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });

    // HalkReportsController Reports Controller
    Route::group(['prefix' => 'halk', 'controller' => HalkReportsController::class, 'as' => 'halk.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });

    // ManufacturingReportsController Reports Controller
    Route::group(['prefix' => 'manufacturing', 'controller' => ManufacturingReportsController::class, 'as' => 'manufacturing.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });

    // OperationsReportsController Reports Controller
    Route::group(['prefix' => 'operations', 'controller' => OperationsReportsController::class, 'as' => 'operations.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });

    // SuppliersReportsController Reports Controller
    Route::group(['prefix' => 'suppliers', 'controller' => SuppliersReportsController::class, 'as' => 'suppliers.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/report', 'report')->name('report');
    });
});

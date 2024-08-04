<?php

use App\Models\materialRecipe;
use App\Http\Controllers\Stock\old;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stock\MaterialHalk;
use App\Http\Controllers\Stock\MaterialTransfer;
use App\Http\Controllers\Stock\OrdersControllers;
use App\Http\Controllers\Stock\ExchangeController;
use App\Http\Controllers\Stock\MaterialOperations;
use App\Http\Controllers\Stock\PurchasesController;
use App\Http\Controllers\Stock\materialManufacturing;
use App\Http\Controllers\Stock\OpenBalanceController;
use App\Http\Controllers\Stock\InDirectCostController;
use App\Http\Controllers\Stock\Stores\StoreController;
use App\Http\Controllers\Stock\BackToStoresControllers;
use App\Http\Controllers\Stock\ComponentItemsController;
use App\Http\Controllers\Stock\Groups\SubGroupController;
use App\Http\Controllers\Stock\BackToSuppliersControllers;
use App\Http\Controllers\Stock\Groups\MainGroupController;
use App\Http\Controllers\Stock\Items\FilterItemController;
use App\Http\Controllers\Stock\OpenBalanceDailyController;
use App\Http\Controllers\Stock\Sections\SectionController;
use App\Http\Controllers\Stock\Material\MaterialController;
use App\Http\Controllers\StockReports\HalkReportsController;
use App\Http\Controllers\Stock\Suppliers\SuppliersController;
use App\Http\Controllers\StockReports\ItemsPricingController;
use App\Http\Controllers\StockReports\StockReportsController;
use App\Http\Controllers\Stock\ComponentDetailsItemController;
use App\Http\Controllers\Stock\Items\ItemComponentsController;
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
use App\Http\Controllers\StockReports\BackStoresReportsController;
use App\Http\Controllers\StockReports\OperationsReportsController;
use App\Http\Controllers\Stock\Items\FilterItemComponentController;
use App\Http\Controllers\StockReports\BackSuppliersReportsController;
use App\Http\Controllers\StockReports\ManufacturingReportsController;
use App\Http\Controllers\Stock\Material\FilterMaterialBranchController;
use App\Http\Controllers\Stock\Material\FilterMaterialRecipeController;
use App\Http\Controllers\Stock\ItemsDetails\FilterItemsDetailsController;
use App\Http\Controllers\Stock\Material\FilterNotRecipeMaterialController;

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
    Route::post('componentWithoutItems', 'componentWithoutItems')->name('componentWithoutItems');
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




######################################## Material Recipe ########################################
Route::group(['prefix' => 'stock', 'controller' => old::class], function () {
    Route::get('materialRecipe', 'index')->name('materialRecipe');
    Route::post('saveMaterialRecipe', 'saveMaterialRecipe')->name('saveMaterialRecipe');
    Route::post('getRecipeMaterialInMaterials', 'getRecipeMaterialInMaterials')->name('getRecipeMaterialInMaterials');
    Route::post('transferMaterialRecipe', 'transferMaterialRecipe')->name('transferMaterialRecipe');
    Route::post('deleteMaterialRecipe', 'deleteMaterialRecipe')->name('deleteMaterialRecipe');
    // This is Routs Reports in page
    Route::post('getMaterialReports', 'getMaterialReports')->name('getMaterialReports');
    Route::post('getMaterialsReports', 'getMaterialsReports')->name('getMaterialsReports');
});

######################################## Purchases #############################################
Route::group(['prefix' => 'stock', 'controller' => PurchasesController::class], function () {
    Route::get('purchases', 'index')->name('purchases');
    Route::post('changePurchasesType', 'changeType')->name('changePurchasesType');
    Route::post('changePurchasesBranch', 'changeBranch')->name('changePurchasesBranch');
    Route::post('changePurchasesSection', 'changeSection')->name('changePurchasesSection');
    Route::post('changePurchasesStore', 'changeStore')->name('changePurchasesStore');
    Route::post('changePurchasesUnit', 'getUnit')->name('changePurchasesUnit');
    Route::post('savePurchase', 'save')->name('savePurchase');
    Route::post('getPurchase', 'getPurchase')->name('getPurchase');
    Route::post('getPurchaseViaSerial', 'getPurchaseViaSerial')->name('getPurchaseViaSerial');
    Route::post('deletePurchase', 'deletePurchase')->name('deletePurchase');
    Route::post('deleteItemPurchase', 'deleteItemPurchase')->name('deleteItemPurchase');
    Route::post('updatePurchase', 'updatePurchase')->name('updatePurchase');
    Route::post('updateItemPurchase', 'updateItemPurchase')->name('updateItemPurchase');
});

Route::group(['prefix' => 'stock', 'controller' => ExchangeController::class], function () {
    Route::get('exchange', 'index')->name('exchange');
    Route::post('saveExchange', 'save')->name('saveExchange');
    Route::post('getExchange', 'getExchange')->name('getExchange');
    Route::post('getExchangeViaSerial', 'getExchangeViaSerial')->name('getExchangeViaSerial');
    Route::post('getExchangeViaOrder', 'getExchangeViaOrder')->name('getExchangeViaOrder');

    Route::post('deleteExchange', 'deleteExchange')->name('deleteExchange');
    Route::post('deleteItemExchange', 'deleteItemExchange')->name('deleteItemExchange');
    Route::post('updateExchange', 'updateExchange')->name('updateExchange');
    Route::post('updateItemExchange', 'updateItemExchange')->name('updateItemExchange');
});

Route::group(['prefix' => 'stock', 'controller' => MaterialTransfer::class], function () {
    Route::get('transfers', 'index')->name('transfers');
    Route::post('changeTransferType', 'changeType')->name('changeTransferType');
    Route::post('saveTransfer', 'save')->name('saveTransfer');
    Route::post('getTransfer', 'getTransfer')->name('getTransfer');
    Route::post('updateTransfer', 'updateTransfer')->name('updateTransfer');
    Route::post('updateItemTransfer', 'updateItemTransfer')->name('updateItemTransfer');
    Route::post('deleteItemTransfer', 'deleteItemTransfer')->name('deleteItemTransfer');
    Route::post('getTransferViaSerial', 'getTransferViaSerial')->name('getTransferViaSerial');
    Route::post('deleteTransfer', 'deleteTransfer')->name('deleteTransfer');
});

Route::group(['prefix' => 'stock', 'controller' => MaterialHalk::class], function () {
    Route::get('halk', 'index')->name('halk');
    Route::post('changeHalkType', 'changeType')->name('changeHalkType');
    Route::post('saveHalk', 'save')->name('saveHalk');
    Route::post('getHalk', 'getHalk')->name('getHalk');
    Route::post('updateHalk', 'updateHalk')->name('updateHalk');
    Route::post('updateItemHalk', 'updateItemHalk')->name('updateItemHalk');
    Route::post('deleteItemHalk', 'deleteItemHalk')->name('deleteItemHalk');
    Route::post('getHalkViaSerial', 'getHalkViaSerial')->name('getHalkViaSerial');
    Route::post('deleteHalk', 'deleteHalk')->name('deleteHalk');
    // Halk Items
    Route::get('halkItem', 'halkItem')->name('halkItem');
    Route::post('save_halk_item', 'save_halk_item')->name('save_halk_item');
    Route::post('deleteHalkItem', 'deleteHalkItem')->name('deleteHalkItem');
    Route::post('getHalkOld', 'getHalkOld')->name('getHalkOld');
});

Route::group(['prefix' => 'stock', 'controller' => BackToSuppliersControllers::class], function () {
    Route::get('back_to_suppliers', 'index')->name('back_to_suppliers');
    Route::post('saveBackToSuppliers', 'save')->name('saveBackToSuppliers');
    Route::post('getBackToSuppliers', 'get')->name('getBackToSuppliers');
    Route::post('getBackToSuppliersViaSerial', 'getViaSerial')->name('getBackToSuppliersViaSerial');
    Route::post('updateBackToSuppliers', 'update')->name('updateBackToSuppliers');
    Route::post('updateItemBackToSuppliers', 'updateItem')->name('updateItemBackToSuppliers');
    Route::post('deleteItemBackToSuppliers', 'deleteItem')->name('deleteItemBackToSuppliers');
    Route::post('deleteBackToSuppliers', 'delete')->name('deleteBackToSuppliers');
});

Route::group(['prefix' => 'stock', 'controller' => BackToStoresControllers::class], function () {
    Route::get('back_to_stores', 'index')->name('back_to_stores');
    Route::post('saveBackToStores', 'save')->name('saveBackToStores');
    Route::post('getBackToStores', 'get')->name('getBackToStores');
    Route::post('getBackToStoresViaSerial', 'getViaSerial')->name('getBackToStoresViaSerial');
    Route::post('deleteBackToStores', 'delete')->name('deleteBackToStores');
    Route::post('updateBackToStores', 'update')->name('updateBackToStores');
    Route::post('updateItemBackToStores', 'updateItem')->name('updateItemBackToStores');
    Route::post('deleteItemBackToStores', 'deleteItem')->name('deleteItemBackToStores');
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

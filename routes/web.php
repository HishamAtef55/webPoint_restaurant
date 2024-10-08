<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Device;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Menu\PayController;
use App\Http\Controllers\Menu\CopyCloseShift;
use App\Http\Controllers\Menu\MenuController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ToGoController;
use App\Http\Controllers\Menu\LoginController;
use App\Http\Controllers\Menu\ShiftController;
use App\Http\Controllers\Admin\AdminController;


use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Menu\MovetoController;
use App\Http\Controllers\Menu\TablesController;
use App\Http\Controllers\Admin\GenralController;
use App\Http\Controllers\Admin\OthersController;
use App\Http\Controllers\Admin\OpenDayController;
use App\Http\Controllers\Menu\CustomerController;
use App\Http\Controllers\Menu\DeliveryController;

use App\Http\Controllers\Admin\DeleveryController;
use App\Http\Controllers\Admin\DiscountController;

use App\Http\Controllers\Admin\MinchargeController;

use App\Http\Controllers\Reports\ReportsController;

use App\Http\Controllers\Menu\ReservationController;

use App\Http\Controllers\Admin\CarServicesController;
use App\Http\Controllers\Admin\InformationController;

use App\Http\Controllers\Admin\Item_DetailsController;
use App\Http\Controllers\Menu\DailyExpensesController;

use App\Http\Controllers\Reports\DailyReportsController;

use App\Http\Controllers\Admin\ExpensesCategoryController;

use App\Http\Controllers\Reports\SalesCurrentDayController;

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
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TablesController as AdminTablesController;

define('TITLE', 'Web Point');
Route::get('/', function () {
  Auth::logout();
  session()->invalidate();
  session()->regenerateToken();
  $users = User::select(['email'])->get();
  return view('auth.login', compact('users'));
});

Route::post('webPoint', [LoginController::class, 'check_admin'])->name('check_admin');

Auth::routes();
Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
  Route::resource('roles', RoleController::class);
  Route::resource('users', UserController::class);
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('costControl', [HomeController::class, 'costControl'])->name('costControl');
############################ Start Routes in Admin Control #####################
Route::group(['prefix' => 'admin'], function () {
  ###################### Start Information And Print  Pages###################
  Route::group(['controller' => InformationController::class], function () {
    Route::post('/SaveInformation', 'save')->name('save.information');
    Route::get('/Information', 'view')->name('view.information');
    Route::post('/UpdateInformation', 'update')->name('update.information');
    Route::post('/GetInformation', 'Get_Inf')->name('get.information');
    Route::post('/SavePrinters', 'save_print')->name('Save.Printers');
    Route::post('/Update_printer', 'update_printers')->name('update.printers.action');
    Route::post('/search_printers', 'search_printers')->name('search.printers.action');
  });

  ############################### Start Device  PAges#########################
  Route::group(['controller' => GenralController::class], function () {
    Route::post('/reset_data', 'reset_data')->name('reset_data_post');
    Route::post('/AddBranch', 'add_branch')->name('add.branch');
    Route::post('/save_location', 'save_location')->name('save.location');
    Route::post('/update_location', 'update_location')->name('update.location');
    Route::post('/Search_location', 'Search_location')->name('Search.location');
    Route::get('/View_branch', 'View_update_branch')->name('View.update.branch');
    Route::post('/AddMenu', 'add_menu')->name('add.menu');
    Route::get('/update_menu', 'view_ubdate_menu')->name('view.update.menu');
    Route::post('/save_group', 'save_group')->name('save.group');
    Route::get('/View_group', 'View_update_group')->name('View.update.group');
    Route::post('/save_subgroup', 'save_subgroup')->name('save.subgroup');
    Route::get('/View_subgroup', 'View_update_subgroup')->name('View.update.subgroup');
    ############################### Start Select Data In General Page#########
    Route::post('/view_select_branch', 'view_select_branch')->name('view.select.branch');
    Route::post('/view_select_menu', 'view_select_menu')->name('view.select.menu');
    Route::post('/view_select_group', 'view_select_group')->name('view.select.group');
    ############################### start shift Data In General Page##########
    Route::post('/Save_shift', 'save_shift')->name('save.shift');
    Route::post('/Search_shift', 'search_shift')->name('search.shift');
    Route::post('/Update_shift', 'tableshift_action')->name('tableshift.action');
    ############################### Start Search Data In General Page#########
    Route::post('/search_branch', 'search_branch')->name('search.branch');
    Route::post('/search_menu', 'search_menu')->name('search.menu');
    Route::post('/search_group', 'search_group')->name('search.group');
    Route::post('/search_subgroup', 'search_subgroup')->name('search.subgroup');
    ############################### Start Add New Tables #####################
    Route::post('/add_new_table', 'add_new_table')->name('add.new.table');
    Route::post('/search_holes', 'search_holse')->name('search.holes');
    Route::post('/search_holse_admin', 'search_holse_admin')->name('search.holes.admin');
    Route::post('/motion_table', 'motion_table')->name('motion.table');
    Route::post('/resize_table', 'resize_table')->name('resize.table');
    Route::post('/add_new_hole', 'add_new_hole')->name('add.new.hole');
    Route::post('/del_hole', 'del_hole')->name('del.hole');
    Route::post('/del_table', 'del_table')->name('del.table');
    Route::post('/get_holes', 'get_holes')->name('get.holes');
    ############################### Start Update In General Page##############
    Route::post('/tablebranch_action', 'branch_action')->name('tablebranch.action');
    Route::post('/tablemenu_action', 'menu_action')->name('tablemenu.action');
    Route::post('/tablegroup_action', 'group_action')->name('tablegroup.action');
    Route::post('/tablesubgroup_action', 'subgroup_action')->name('tablesubgroup.action');
    Route::post('/save_printers', 'save_printers')->name('save.printers');
  });

  ############################### Start Device  Page##########################
  Route::group(['controller' => Device::class], function () {
    Route::get('/Add_Device', 'Add_Device')->name('View.device')->middleware('auth');
    Route::post('/Upload_device', 'upload_device')->name('upload.device');
  });

  ############################### Start OpenDayController  Page##########################
  Route::group(['controller' => OpenDayController::class], function () {
    Route::get('/getDays', 'index')->name('View.getDays')->middleware('auth');
    Route::post('/getDaysUsingBranch', 'getDaysUsingBranch')->name('getDays.getDaysUsingBranch');
    Route::post('/getDays-openDay', 'openDay')->name('getDays.openDay');
    Route::post('/getDays-emptyTable', 'emptyTable')->name('getDays.emptyTable');
  });

  ############################### Start Include PAges#########################
  Route::group(['controller' => AdminController::class], function () {
    Route::get('/genral', 'View_General')->name('View.General');
    Route::get('/Item', 'View_AddItem')->name('View.AddItem');
    Route::get('/User', 'View_AddUser')->name('View.AddUser');
    Route::get('/Item_Extra', 'View_ItemsDetails')->name('View.ItemsDetails');
    Route::get('/Discount', 'View_Discount')->name('View.Discount');
    Route::get('/ServicesGeneral', 'View_ServicesGeneral')->name('View.ServicesGeneral');
    Route::get('/CarServices', 'View_CarServices')->name('View.CarServices');
    Route::get('/Delivery', 'View_Delivery')->name('View.Delivery');
    Route::get('/Tabels', 'View_Tabels')->name('View.Tabels');
    Route::get('/TakeAway', 'View_TakeAway')->name('View.TakeAway');
    Route::get('/Other', 'View_Other')->name('View.Other');
    Route::get('/Add_Details', 'View_Add_details')->name('View.AddDetails');
    Route::get('/Add_Tables', 'Add_Tables')->name('View.Add.Tables');
    Route::get('/Add_Extra', 'Add_Extra')->name('View.Add.Extra');
    Route::get('/Add_Location', 'add_location')->name('View.Add.Location');
    Route::get('/Add_shift', 'add_shift')->name('View.Add.shift');
    Route::get('/view_printers', 'view_printers')->name('view.printers');
    Route::get('/Reset', 'reset_data')->name('reset_data');
    Route::get('/Del_subgroup/{subgroup_id}', 'Del_subgroup')->name('test.test');
    Route::post('/update_branch', 'update_branch')->name('update.branch');
  });

  ######################## Start Item Menu  ##################################
  Route::group(['controller' => ItemController::class], function () {
    Route::post('/save_item', 'save_item')->name('save.item');
    Route::post('/save_extra', 'save_extra')->name('save.extra');
    Route::post('/search_item', 'search_item')->name('search.item');
    Route::post('/search_item_new', 'search_item_newfunction')->name('search.item_new_up');
    Route::get('/View_item', 'View_update_item')->name('View.update.item');
    Route::post('/tableitem_action', 'action')->name('tableitem.action');
    Route::post('/itemwithoutprinter', 'itemWithOutPrinter')->name('itemWithOutPrinter');
    Route::post('/show_all_item', 'show_all_item')->name('show_all_item');
    Route::post('/update_item_price', 'update_item_price')->name('update_item_price');
    Route::post('/update_item_active', 'update_item_active')->name('update_item_active');
    Route::post('/del_item_action', 'de_action')->name('del_item_action');
    Route::post('/viewselect_subgroup', 'select_item_sub_group')->name('select.item.sub.group');
    Route::post('/search_select_item', 'search_select_item')->name('search.select.item');
    Route::post('/search_select_extra', 'search_select_extra')->name('search.select.extra');
    Route::post('/export_extra', 'export_extra')->name('export.extra');
    Route::post('/update_export_extra', 'update_export_extra')->name('update.export.extra');
    Route::post('/delete_export_extra', 'delete_export_extra')->name('delete.export.extra');
    Route::post('/get_item_extra', 'get_item_extra')->name('get.item.extra');
  });

  ############################### Start User Page#############################
  Route::group(['controller' => AdminUserController::class], function () {
    Route::post('/save_user', 'save_user')->name('save.user');
    Route::get('/View_update_user', 'View_update_user')->name('View.update.user');
    Route::post('/search_user', 'search_user')->name('search.user');
    Route::post('/tableuser_action', 'action')->name('tableuser.action');
  });

  ############################### Start Item Details Page#####################
  Route::group(['controller' => Item_DetailsController::class], function () {
    Route::post('/Select_Data', 'item_detalis')->name('item.detalis');
    Route::post('/extract_details_table', 'extract_details_table')->name('extract.details.table');
    Route::post('/save_item_details', 'save_item_details')->name('save.item.details');
    Route::post('/Search_item_details', 'Search_item_details')->name('Search.item.details');
    Route::post('/Delete_item_details', 'delete_item_details')->name('delete.item.details');
    Route::post('/export_details', 'export_details')->name('export.details');
    Route::post('/update_dettails_price', 'update_dettails_price')->name('update.dettails.price');
    Route::post('/search_details', 'search_details')->name('search.details');
    Route::post('/save_details', 'save_details')->name('save.details');
    Route::post('/action_edite', 'action_edite')->name('action.edite');
    Route::get('/view_details_selected_action', 'view_details_selected_action')->name('view.details.selected.action');
    Route::post('/details_selected_action', 'details_selected_action')->name('details.selected.action');
    Route::post('/details_selected', 'details_selected')->name('details.selected');
    Route::post('/view_select_item', 'view_select_item')->name('view.select.item');
  });

  ############################## Start Discount Page##########################
  Route::group(['controller' => DiscountController::class], function () {
    Route::post('/Save_Discount', 'save_discount')->name('save.discount');
    Route::post('/Search_Discount', 'search_discount')->name('search.discount');
    Route::get('/Update_Discount', 'view_update_discount')->name('view.update.discount');
    Route::post('/tablediscount_action', 'action')->name('tablediscount.action');
  });

  ############################ Start Route Delevery    #######################
  Route::group(['controller' => DeleveryController::class], function () {
    Route::get('/View_Delivery', 'view_del')->name('view.del');
    Route::post('/save_Delivery', 'save_del')->name('save.del');
    Route::post('/Get_Delivery', 'get_del')->name('get.del');
  });

  ############################ Start Route TOGO    ###########################
  Route::group(['controller' => ToGoController::class], function () {
    Route::get('/View_TOGO', 'to_go')->name('to.go');
    Route::post('/save_TOGO', 'save_TOGO')->name('save.togo');
    Route::post('/Get_TOGO', 'Get_TOGO')->name('get.togo');
  });

  ############################ Start Route Other    ###########################
  Route::group(['controller' => OthersController::class], function () {
    Route::get('/View_Other', 'view_other')->name('view.other');
    Route::post('/save_Other', 'save_Other')->name('save.other');
    Route::post('/Get_Other', 'Get_Other')->name('get.other');
  });

  ############################ Start Route Services Tables    ################
  Route::group(['controller' => AdminTablesController::class], function () {
    Route::get('/View_Service_Tables', 'view_ser_table')->name('view.ser.table');
    Route::post('/save_ser_table', 'save_ser_table')->name('save.ser.table');
    Route::post('/Get_Ser_Table', 'Get_ser_table')->name('get.ser.table');
  });

  ############################ Start Route Services Tables    ################
  Route::group(['controller' => CarServicesController::class], function () {
    Route::get('/View_Car_Services', 'view_car_cervicese')->name('view.car.cervices');
    Route::post('/save_car_cervicese', 'save_car_cervicese')->name('save.car.cervices');
    Route::post('/Get_car_cervicese', 'Get_car_cervicese')->name('get.car.cervices');
  });

  ############################ Start Route Services Tables    ###########################
  Route::group(['controller' => MinchargeController::class], function () {
    Route::get('/View_charge', 'view_mincharge')->name('view.mincharge');
    Route::post('/save_mincharge', 'save_mincharge')->name('save.mincharge');
    Route::post('/Get_mincharge', 'Get_mincharge')->name('get.mincharge');
    Route::post('/Save_all_min', 'Save_all_min')->name('Save.all.min');
    Route::post('/Save_one_min', 'Save_one_min')->name('Save.one.min');
    Route::post('/change_charge', 'change_charge')->name('change.charge');
  });

  ################################ ExpensesCategoryController ######################
  Route::group(['controller' => ExpensesCategoryController::class], function () {
    Route::get('/expenses', 'index')->name('expenses.index');
    Route::post('/expenses/search', 'search')->name('expenses.search');
    Route::post('/expenses/save', 'save')->name('expenses.save');
    Route::post('/expenses/action', 'save')->name('expenses.action');
  });
});

############################ Start Routes in Menu Control #####################
Route::group(['prefix' => 'menu'], function () {

  // Route::post('/Tables',[LoginController::class,'check_admin'])->name('check.user');

  ############################## MenuController #####################################
  Route::group(['controller' => MenuController::class], function () {
    Route::get('/Menu', 'view_menu')->name('view.menu');
    Route::get('/Show_Table', 'view_table')->name('view.table');
    Route::post('/Import_Items', 'import_items')->name('import.items');
    Route::post('/Wait_Items', 'wait_items')->name('wait.items');
    Route::post('/Delete_Order', 'Delete_Order')->name('delete.order');
    Route::post('/Comment_Order', 'Comment_Order')->name('comment.order');
    Route::post('/Without.order', 'without_order')->name('without.order');
    Route::post('/occupy_table', 'occupy_table')->name('occupy.table');
    Route::post('/Discount_items', 'Discount_items')->name('Discount.items');
    Route::post('/Discount_all', 'Discount_all')->name('Discount.all');
    Route::post('/delete_discount', 'delete_discount')->name('delete.discount');
    Route::post('/add_details_wait', 'add_details_wait')->name('add.details.wait');
    Route::post('/find_extra_item', 'find_extra_item')->name('find.extra.item');
    Route::post('/find_materials_item', 'find_materials_item')->name('find.materials.item');
    Route::post('/export_Extra_menu', 'export_Extra')->name('export.Extra.menu');
    Route::post('/Change_Menu', 'change_menu')->name('change.menu');
    Route::post('/getnewsub', 'getnewsub')->name('getnewsub.menu');
    Route::post('/TakeOrder', 'take_order')->name('take.order');
    Route::post('/CheckService', 'CheckService')->name('CheckService');
  });

  ############################ PayController ###########################
  Route::group(['controller' => PayController::class], function () {
    Route::post('/Pay', 'Pay')->name('Pay.check');
    Route::post('/PayCheck', 'Pay_check')->name('Pay.check.money');
    Route::post('/print_check', 'print_check')->name('print.check');
  });
  ############################ TablesController    ###########################
  Route::group(['controller' => TablesController::class], function () {
    Route::get('/New_Order/{table_id}', 'new_order')->name('new.order');
    Route::post('/Get_users', 'get_users')->name('get.users');
    Route::post('/Transfer_users', 'transfer_users')->name('transfer.users');
    Route::post('/Get_Wait_Transfer', 'Get_Wait_Transfer')->name('get.wait.transfer');
    Route::post('/Opration_Transfer', 'opration_transfer')->name('opration.transfer');
    Route::post('/Get_reservation', 'get_reservation')->name('get.res');
    Route::post('/Del_reservation', 'del_reservation')->name('del.res');
    Route::post('/get_total_table', 'get_total_table')->name('get.total.table');
    Route::post('/Save_merge', 'Save_merge')->name('Save.merge');
  });

  ############################## Close Shift #####################################
  Route::post('/Close_Shift', [ShiftController::class, 'close_shift'])->name('close.shift');

  ############################ Start Route Moveto    ###########################
  Route::group(['controller' => MovetoController::class], function () {
    Route::get('/MoveTo', 'moveto')->name('move.to');
    Route::post('/view_main_table', 'search_main_table')->name('search.main.table');
    Route::post('/view_new_table', 'search_new_table')->name('search.new.table');
    Route::post('/moveto_item', 'moveto_item')->name('move.to.item');
    Route::get('/CopyCheck', 'copy_check')->name('copy.check');
    Route::post('/View_check', 'view_check')->name('view.check');
    Route::post('/print_copy_check', 'print_copy_check')->name('print_copy_check');
  });

  ############################  Start Routes Customer    #########################
  Route::group(['controller' => CustomerController::class], function () {
    Route::post('/Save_customer', 'save_customer')->name('Save.customer');
    Route::post('/search_customer', 'search_customer')->name('search.customer');
    Route::post('/update_customer', 'update_customer')->name('update.customer');
    Route::post('/order_customer', 'order_customer')->name('order.customer');
  });

  ############################  Start Routes Reservation    ###########################
  Route::post('/save_reservation', [ReservationController::class, 'save_reservation'])->name('save.reservation');
  ############################  End Routes Reservation    ###########################

  ############################  Start Routes Delivery    ###########################
  Route::group(['controller' => DeliveryController::class], function () {
    Route::get('/Delivery_Order', 'Delivery_Order')->name('Delivery.Order');
    Route::get('/Delivery_to_pilot', 'to_pilot')->name('delivery.to.pilot');
    Route::get('/Delivery_holding_list', 'hold_list')->name('delivery.hold.list');
    Route::get('/TOGO_holding_list', 'hold_list_togo')->name('togo.hold.list');
    Route::get('/Delivery_pilot_account', 'pilot_account')->name('delivery.pilot.account');
    Route::get('/Edit_Order/{order_id}', 'edit_order')->name('edite.order.delivery');
    Route::post('/Remove_Delivery', 'Remove_Delivery')->name('Remove.Delivery');
    Route::post('/add_pilot_delivery', 'add_pilot_delivery')->name('add.pilot.Delivery');
    Route::post('/Search_order_delivery', 'Search_order_delivery')->name('Search.order.delivery');
    Route::post('/Search_pilot_delivery', 'Search_pilot_delivery')->name('Search.pilot.delivery');
    Route::post('/Save_hold_delivery', 'Save_hold_delivery')->name('Save.hold.delivery');
    Route::post('/take_order_hold', 'take_order_hold')->name('take.order.hold.delivery');
    Route::get('/TOGO_Order', 'Togo_Order')->name('view.togo.Order');
    Route::post('/done_order_delivery', 'done_order_delivery')->name('done.order.delivery');
    Route::post('/takeOrderHold', 'takeOrderHold')->name('takeOrderHold');
    Route::post('/takeOrderHoldByOrders', 'takeOrderHoldByOrders')->name('takeOrderHoldByOrders');
  });
  ############################  End Routes Delivery    ###########################

  ############################  Start Routes CopyClose Shift    #####################
  Route::group(['controller' => CopyCloseShift::class], function () {
    Route::get('/Copy_close_shift', 'index')->name('copy.close_shift');
    Route::post('/View_copy_close_check', 'view_close_check')->name('view.copy_close_check');
    Route::post('/print_copy_close_check', 'print_close_shift')->name('print.copy_close_check');
  });
  ############################  End Routes Delivery    ###########################

  ############################  Start Routes ExpensesCategory    #####################
  Route::group(['controller' => DailyExpensesController::class], function () {
    Route::get('/DailyExpenses', 'index')->name('DailyExpenses');
    Route::post('/SaveExpenses', 'save')->name('DailyExpenses.save');
    Route::post('/DeleteExpenses', 'delete')->name('DailyExpenses.delete');
  });
  ############################  End Routes Delivery    ###########################
});

############################ Start Routes in Reports Control ###################
Route::group(['prefix' => 'Reports'], function () {
  ################ Get ReportsController Pages ###############################
  Route::group(['controller' => ReportsController::class], function () {
    ################ Get Reports Pages #######################################
    Route::get('/daily', 'view_daily')->name('daily_report');
    Route::get('/Sales-Current', 'view_sales_current')->name('view_sales_current');
    Route::get('/Daily-Report', 'view_daily_report')->name('view_daily_report');

    ################ Get Reports Pages #######################################
    Route::get('/view_water_sales_report', 'view_water_sales_report')->name('view_water_sales_report');
    Route::post('/search_water_sales_report', 'search_water_sales_report')->name('search_water_sales_report');

    ################################ Shift Sales Reports  ####################
    Route::get('/view_shift_sales_report', 'view_shift_sales_report')->name('view_shift_sales_report');
    Route::post('/search_shift_sales_report', 'search_shift_sales_report')->name('search_shift_sales_report');

    ################################ Transfers Reports    ####################
    Route::get('/view_transfer_report', 'view_transfer_report')->name('view_transfer_report');
    Route::post('/search_transfer_report', 'search_transfer_report')->name('search_transfer_report');

    ################################ Discount Reports     ####################
    Route::get('/view_discount_report', 'view_discount_report')->name('view_discount_report');
    Route::post('/search_discount_report', 'search_discount_report')->name('search_discount_report');

    ################################ Void Reports         ####################
    Route::get('/view_void_report', 'view_void_report')->name('view_void_report');
    Route::post('/search_void_report', 'search_void_report')->name('search_void_report');

    ################################ Void Reports         ####################
    Route::get('/view_expenses_report', 'view_expenses_report')->name('view_expenses_report');
    Route::post('/expensesReport', 'expensesReport')->name('expensesReport');

    ################################## SAles Item Report #####################
    Route::get('/view_item_report', 'view_item_report')->name('view_item_report');
    Route::post('/search_item_report', 'search_item_report')->name('search_item_report');

    ######################### Cost Report ####################################
    Route::get('/view_cost_report', 'view_cost_report')->name('view_cost_report');
    Route::post('/costReport', 'costReport')->name('costReport');

    ######################### Log Report ####################################
    Route::get('/view_log_report', 'view_log_report')->name('view_log_report');
    Route::post('/logReport', 'logReport')->name('logReport');
    Route::post('/viewLog', 'viewLog')->name('viewLog');

    ############################## view_cost_sold_report #####################
    Route::get('/view_cost_sold_report', 'view_cost_sold_report')->name('view_cost_sold_report');
    Route::post('/cost_sold_report', 'cost_sold_report')->name('cost_sold_report');
  });

  ################ Get SalesCurrentDayController Pages #######################
  Route::group(['controller' => SalesCurrentDayController::class], function () {
    Route::post('/cashier_report', 'cashier_report')->name('cashier_report');
    Route::post('/cashier_report_sold', 'cashier_report_sold')->name('cashier_report_sold');
  });

  ################ Get DailyReportsController Pages ##########################
  Route::group(['controller' => DailyReportsController::class], function () {
    Route::post('/daily_report', 'daily_report')->name('daily_report');
    Route::post('/daily_sold_report', 'daily_sold_report')->name('daily_sold_report');
  });
});

include __DIR__  . '/stock.php';

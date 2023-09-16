<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OthersRequest;
use App\Models\Branch;
use App\Models\Others;
use Illuminate\Http\Request;

class OthersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');
    }
    public function view_other()
    {
        $branchs = Branch::get()->all();
        return view('control.add_Other',compact('branchs'));
    }

    public function save_other(OthersRequest $request)
    {
        $data_array = array(
            'close_day'                         =>$request->close_day,
            'allow_update'						=>$request->allow_update,
            'drawer_printer_check'			    =>$request->drawer_printer_check,
            'allow_void'						=>$request->allow_void,
            'void_priming'						=>$request->void_priming,
            'display_modify'					=>$request->display_modify,
            'display_total'						=>$request->display_total,
            'display_waiter'					=>$request->display_waiter,
            'item_tax'							=>$request->item_tax,
            'item_service'						=>$request->item_service,
            'display_addition'					=>$request->display_addition,
            'employees_shift'					=>$request->employees_shift,
            'time_attendance'					=>$request->time_attendance,
            'close_day_auto'					=>$request->close_day_auto,
            'close_day_table'					=>$request->close_day_table,
            'compo'								=>$request->compo,
            'promotions'						=>$request->promotions,
            'malt_pass_security'				=>$request->malt_pass_security,
            'over_sub'							=>$request->over_sub,
            'display_visa'						=>$request->display_visa,
            'display_ledge'						=>$request->display_ledge,
            'display_officer'					=>$request->display_officer,
            'dis_hospitality'					=>$request->dis_hospitality,
            'dis_save'							=>$request->dis_save,
            'dis_save_print'					=>$request->dis_save_print,
            'dis_keyboard'						=>$request->dis_keyboard,
            'dis_tip_cash'						=>$request->dis_tip_cash,
            'del_data'							=>$request->del_data,
            'print_reports'						=>$request->print_reports,
            'print_void_slip'					=>$request->print_void_slip,
            'collect_items_check'				=>$request->collect_items_check,
            'collect_items_slip'				=>$request->collect_items_slip,
            'items_qty'							=>$request->items_qty,
            'decimal_qty'						=>$request->decimal_qty,
            'delivery_reciving_customer'		=>$request->delivery_reciving_customer,
            'check_balance'						=>$request->check_balance,
            'flash_reports'						=>$request->flash_reports,
            'def_transaction'					=>$request->def_transaction,
            'expeneses'							=>$request->expeneses,
            'copy_invoice'						=>$request->copy_invoice,
            'branch'							=>$request->branch,
            'drawer_printer'					=>$request->drawer_printer,
            'transaction_printer'				=>$request->transaction_printer,
            'printer'							=>$request->printer,
            'printers_Invoice'					=>$request->printers_Invoice,
            'reservation_copies'				=>$request->reservation_copies,
            'transaction_copies'				=>$request->transaction_copies,
            'fast_checkout'						=>$request->fast_checkout,
        );
        if(Others::where('branch','=',$request->branch)->count() > 0)
        {
            $data = Others::where('branch',$request->branch)
                ->update($data_array);
        }else
        {
            $data = Others::create($data_array);
        }

        if($data)
        {
            return response()->json([
                'status' =>true,
            ]);
        }

    }

    public function get_other(Request $request)
    {
        $data = Others::where('branch',$request->branch)
            ->get();
        return response()->json($data);
    }
}

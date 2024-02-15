<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicesRequest;
use App\Models\Branch;
use App\Models\Printers;
use App\Models\Service_tables;
use Illuminate\Http\Request;

class TablesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:admin');

    }
    public function view_ser_table()
    {
        $branchs = Branch::get()->all();
        $printers = Printers::where(['active'=>'1','branch_id'=>auth()->user()->branch_id])->get();
        return view('control.services_tables',compact('branchs','printers'));
    }

    public function save_ser_table(ServicesRequest $request)
    {
        $data_array = array(
            'fast_checkout'         =>$request->fast_checkout,
            'print_invoic'          =>$request->print_invoic,
            'reser_recipt'          =>$request->reser_recipt,
            'invoice_payment'       =>$request->invoice_payment,
            'payment_teble'         =>$request->payment_teble,
            'invoic_teble'          =>$request->invoic_teble,
            'end_teble'             =>$request->end_teble,
            'vou_copon'             =>$request->vou_copon,
            'mincharge_screen'      =>$request->mincharge_screen,
            'display_table'         =>$request->display_table,
            'receipt_checkout'      =>$request->receipt_checkout,
            'receipt_send'          =>$request->receipt_send,
            'printers_input'        =>$request->printer,
            'slip_all'              =>$request->slip_all,
            'slip_copy'             =>$request->slip_copy,
            'pr_reservation'        =>$request->pr_reservation,
            'car_receipt'           =>$request->car_receipt,
            'print_slip'            =>$request->print_slip,
            'invoic_copies'         =>$request->invoic_copies,
            'min_charge'            =>$request->min_charge,
            'tax_service'           =>$request->tax_service,
            'service_ratio'         =>$request->service_ratio,
            'tax'                   =>$request->tax,
            'branch'                =>$request->branch,
            'discount_tax_service'  =>$request->discount_tax_service,
            'r_bank'                =>$request->bank_ratio,
            'printer_shift'         =>$request->printer_shift,
        );
        if(Service_tables::where('branch','=',$request->branch)->count() > 0)
        {
            $data = Service_tables::where('branch',$request->branch)
                ->update($data_array);
        }else
        {
            $data = Service_tables::create($data_array);
        }

        if($data)
        {
            return response()->json([
                'status' =>true,
            ]);
        }

    }

    public function Get_ser_table(Request $request)
    {

        $data = Service_tables::where('branch',$request->branch)
            ->get();
        return response()->json($data);
    }
}

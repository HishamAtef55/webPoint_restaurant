<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToGoRequest;
use App\Models\Branch;
use App\Models\ToGo;
use App\Models\Printers;
use Illuminate\Http\Request;

class ToGoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');

    }
    public function to_go()
    {
        $branchs = Branch::get()->all();
        $printers = Printers::where(['active'=>'1'])->get();

        return view('control.Togo',compact('branchs','printers'));
    }

    public function save_togo(ToGoRequest $request)
    {
        $data_array = array(
            'tax'                           =>$request->tax,
            'printer'                       =>$request->printer,
            'invoice_copies'                =>$request->invoice_copies,
            'service_ratio'                 =>$request->service_ratio,
            'print_slip'                    =>$request->print_slip,
            'print_togo'                    =>$request->print_togo,
            'display_checkout_screen'       =>$request->display_checkout_screen,
            'print_reservation_receipt'     =>$request->print_reservation_receipt,
            'print_invice'                  =>$request->print_invice,
            'fast_check'                    =>$request->fast_check,
            'convert_togo_table'            =>$request->convert_togo_table,
            'branch'                        =>$request->branch,
            'discount_tax_service'          =>$request->discount_tax_service,
        );
        if(ToGo::where('branch','=',$request->branch)->count() > 0)
        {
            $data = ToGo::where('branch',$request->branch)
                ->update($data_array);
        }else
        {
            $data = ToGo::create($data_array);
        }

        if($data)
        {
            return response()->json([
                'status' =>true,
            ]);
        }

    }

    public function get_togo(Request $request)
    {
        $data = ToGo::where('branch',$request->branch)
            ->get();
        return response()->json($data);
    }
}

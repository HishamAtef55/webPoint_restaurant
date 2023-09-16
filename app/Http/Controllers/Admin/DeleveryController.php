<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleveryRequest;
use App\Models\Branch;
use App\Models\Delavery;
use App\Models\Printers;
use Illuminate\Http\Request;

class DeleveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');
    }
    public function view_del()
    {
        $branchs = Branch::get()->all();
        $printers = Printers::where(['active'=>'1'])->get();

        return view('control.add_Delivery',compact('branchs','printers'));
    }

    public function save_del(DeleveryRequest $request)
    {
        if(Delavery::where('branch','=',$request->branch)->count() > 0)
        {
            $data = Delavery::where('branch',$request->branch)
                ->update([
                'Pay_copies'       =>$request->Pay_copies,
                'pilot_copies'     =>$request->pilot_copies,
                'print_invoice'    =>$request->print_invoice,
                'print_pilot_slip' =>$request->print_pilot_slip,
                'print_slip'       =>$request->print_slip,
                'printer'          =>$request->printer,
                'ser_ratio'        =>$request->ser_ratio,
                'tax'              =>$request->tax,
                'type_ser'         =>$request->type_ser,
                'user_slip'        =>$request->user_slip,
                'discount_tax_service'=>$request->discount_tax_service,
            ]);
        }else
        {
            $data = Delavery::create([
                'Pay_copies'       =>$request->Pay_copies,
                'branch'           =>$request->branch,
                'pilot_copies'     =>$request->pilot_copies,
                'print_invoice'    =>$request->print_invoice,
                'print_pilot_slip' =>$request->print_pilot_slip,
                'print_slip'       =>$request->print_slip,
                'printer'          =>$request->printer,
                'ser_ratio'        =>$request->ser_ratio,
                'tax'              =>$request->tax,
                'type_ser'         =>$request->type_ser,
                'user_slip'        =>$request->user_slip,
            ]);
        }

        if($data)
        {
            return response()->json([
                'status' =>true,
            ]);
        }

    }

    public function get_del(Request $request)
    {
        $data = Delavery::where('branch',$request->branch)
            ->get();
        return response()->json($data);
    }
}

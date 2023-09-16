<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Car_services;
use Illuminate\Http\Request;
use App\Http\Requests\CarServicesRequest;

class CarServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');

    }
    public function view_car_cervicese()
    {
        $branchs = Branch::get()->all();

        return view('control.Car_services',compact('branchs'));
    }

    public function save_car_cervicese(Request $request)
    {

        $data_array = array(
            'fast_check'                  =>$request->fast_check,
            'printers_input'              =>$request->printers_input,
            'print_invoice'               =>$request->print_invoice,
            'service_ratio'               =>$request->service_ratio,
            'slip'                        =>$request->slip,
            'invoice_copies'              =>$request->invoice_copies,
            'tax'                         =>$request->tax,
            'car_service_receipt'         =>$request->car_service_receipt,
            'reservation_receipt'         =>$request->reservation_receipt,
            'branch'                      =>$request->branch,
        );
        if(Car_services::where('branch','=',$request->branch)->count() > 0)
        {
            $data = Car_services::where('branch',$request->branch)
                ->update($data_array);
        }else
        {
            $data = Car_services::create($data_array);
        }

        if($data)
        {
            return response()->json([
                'status' =>true,
            ]);
        }

    }

    public function Get_car_cervicese(Request $request)
    {
        $data = Car_services::where('branch',$request->branch)
            ->get();
        return response()->json($data);
    }
}

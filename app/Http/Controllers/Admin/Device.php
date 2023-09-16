<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DeviceRequest;
use App\Models\Branch;
use App\Models\Printers;
use App\Traits\All_Functions;

class Device extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Functions;
    public function Add_Device()
    {
        $branchs = Branch::get()->all();
        $printers = Printers::where(['active'=>'1'])->get();
        return view('control.add_device',compact('branchs','printers'));
    }

    public function upload_device(DeviceRequest $request)
    {
        $branch = 0;
        if(isset($request->op)){
            $branch = $this->GetBranch();
        }else{
            $branch = $request->Branch;
        }
        if(\App\Models\Device::where(['branch_id'=>$branch , 'id_device'=>$request -> ID_DEV])->count() > 0){
            return response()->json([
                'status'=>true
            ]);
        }else{
            $save_device = \App\Models\Device::create
            ([
                'id_device'       => $request -> ID_DEV,
                'branch_id'       => $branch,
                'printer_invoice' => $request->printer
            ]);
            if($save_device)
            {
                return response()->json([
                    'status'=>true
                ]);
            }
        }
    }
}

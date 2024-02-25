<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DeviceRequest;
use App\Models\Branch;
use App\Models\Printers;
use App\Models\DevicePrinters;
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
        $printers = Printers::where(['active'=>'1','branch_id'=>auth()->user()->branch_id])->get();
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

        \App\Models\Device::where(['branch_id'=>$branch , 'id_device'=>$request->ID_DEV])->delete();
        DevicePrinters::where(['branch_id'=>$branch,'device_id'=>$request->ID_DEV])->delete();
        $save_device = \App\Models\Device::create
        ([
            'id_device'       => $request -> ID_DEV,
            'branch_id'       => $branch,
            'printer_invoice' => $request->printer
        ]);
        if($save_device)
        {
            foreach($request->selectedPrinters as $row){
                DevicePrinters::create([
                    'printer_id' => $row['id'],
                    'printer'    => $row['name'],
                    'device_id'  => $request->ID_DEV,
                    'branch_id'  => $branch
                ]);
            }
            return response()->json([
                'status'=>true
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Printers;
use App\Models\Informations_System;
use App\Traits\All_Functions;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    use All_Functions;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');

    }
    public function view(){
        $branchs = Branch::get()->all();
        return view('control.informations',compact('branchs'));
    }

    public function save(Request $request){
        $image  = '';
        $slogan = '';

        if($request->image != null){
            $image = $this->saveimage($request->image, 'control/images/information');
        }
        if($request->slogan != null){
            $slogan = $this->saveimage($request->slogan, 'control/images/information');
        }
        if(Informations_System::limit(1)->where(['branch_id'=>$request->branch])->count() > 0){
            return response()->json([
                'status'=>'true',
                'message'=>'This Data Is Oready Exists',
            ]);
        }else{
            $save = Informations_System::create([
                'branch_id' =>$request->branch,
                'name'      =>$request->name,
                'phone'     =>$request->phone,
                'image'     =>$image,
                'slogan'    =>$slogan,
                'note'      =>$request->note,
            ]);
            if($save){
                return response()->json([
                    'status'=>'true',
                    'message'=>'Saved',
                ]);
            }
        }

    }

    public function Get_Inf(Request $request){
        $data = Informations_System::limit(1)->where(['branch_id'=>$request->branch])->first();
        if($data){
            return response()->json([
                'status' => 'true',
                'information'   => $data
            ]);
        }
    }

    public function Update(Request $request){
        $image  = null;
        $slogan = null;
        if ($request->image != null) {
            $image = $this->saveimage($request->image, 'control/images/information');
        }else{
            $data = Informations_System::limit(1)->where(['branch_id'=>$request->branch])->select(['image'])->first();
            $image = $data->image;
        }

        if ($request->slogan != null) {
            $slogan = $this->saveimage($request->slogan, 'control/images/information');
        }else{
            $data = Informations_System::limit(1)->where(['branch_id'=>$request->branch])->select(['slogan'])->first();
            $slogan = $data->slogan;
        }

        $save = Informations_System::limit(1)->where(['branch_id'=>$request->branch])->update([
            'name'      =>$request->name,
            'phone'     =>$request->phone,
            'image'     =>$image,
            'slogan'    =>$slogan,
            'note'      =>$request->note,
        ]);
        if($save){
            return response()->json([
                'status'=>'true',
                'message'=>'Updated',
            ]);
        }
    }

    public function save_print(Request $request){
        $save = Printers::create([
            'branch_id' =>$request->branch,
            'printer'   =>$request->printer,
            'active'    => '1'
        ]);
        if($save){
            return response()->json([
                'status'=>'true',
                'message' => 'Printer Saved'
            ]);
        }
    }

    public function update_printers(Request $request){
        if($request->action == 'edit')
        {
            $data = array(
                'printer'	=>	$request->printer,
            );

            $save = Printers::where('id', $request->id)->update($data);
            if($save){
                return response()->json([
                    'status'  =>'true',
                    'message' => 'Printer Updated'

                ]);
            }
        }else{
            $del = Printers::where('id', $request->id)->delete();
            if($del){
                return response()->json([
                    'status'  => 'true',
                    'message' => 'Printer Deleted',
                    'id'      => $request->id,
                    'action'  => 'delete'
                ]);
            }
        }
    }

    public function search_printers(Request $request){
        if($request->ID != null){
            $query = $request->get('query');
            $data = Printers::where('branch_id', $request->ID)
                ->where('printer', 'LIKE', '%' . $query . "%")
                -> get();
            if($data){
                return response()->json([
                    'status'  =>'true',
                    'printers' => $data
                ]);
            }
        }else{
            return response()->json([
                'status'  =>'flase',
                'printers' => 'Please Select Branch'
            ]);
        }
    }
}

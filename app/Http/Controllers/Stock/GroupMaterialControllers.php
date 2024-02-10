<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockGroupRequest;
use App\Models\MainGroup;
use App\Models\stocksection;
use Illuminate\Http\Request;
use App\Models\material_group;
use Illuminate\Support\Facades\DB;

class GroupMaterialControllers extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    private function getNextUserId()
    {
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'stock_material_groups'");
        $nextUserId = $statement[0]->Auto_increment;
        return $nextUserId;
    }
    public function view_groups(){
        $new_group = $this->getNextUserId();
        $mainGroup = MainGroup::get()->all();
        $groups = material_group::with('maingroup')->get()->all();
        return view('stock.stock.groups',compact('new_group','mainGroup','groups'));
    }
    public function save_groups(StockGroupRequest $request){
        $flag = 0;
        $status = '';
        $msg = '';
        $all_groups = material_group::select(['id','start_serial','end_serial'])->get();
        foreach ($all_groups as $group){
            if($group->start_serial <= $request->from && $group->end_serial >= $request->from){$flag = 1;}
            if($group->start_serial >= $request->to && $group->end_serial <= $request->to){$flag = 1;}
        }
        if($flag == 0){
            $save_group = material_group::create([
                'name'        =>$request->name,
                'start_serial'=>$request->from,
                'end_serial'  =>$request->to,
                'main_group'  =>$request->main_group,
            ]);
            $status ='true';
            $msg = 'تم حفظ المجموعه';
        }else{
            $status ='false';
            $msg = 'هذا الترقيم يتعارض مع مجموعه اخري';
        }
            $new_group = $this->getNextUserId();
            return response()->json(['status'=>$status,'msg'=>$msg,'new_group'=>$new_group]);
    }
    public function search_groups(Request $request){
        $data = material_group::where(['main_group'=>$request->branch])->where('name','like','%'.$request['query'].'%')->get();
        if($data){return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$data]);}
    }
    public function get_groups(Request $request){
        $data = material_group::limit(1)->where('id',$request->id)->first();
        return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$data]);
    }
    public function update_groups(Request $request){
        $flag = 0;
        $status = '';
        $msg = '';
        $all_groups = material_group::select(['id','start_serial','end_serial','name'])->get();
        foreach ($all_groups as $group){
            if($group->id != $request->id){
                if($group->start_serial <= $request->from && $group->end_serial >= $request->from){$flag = 1;}
                if($group->start_serial <= $request->to && $group->end_serial >= $request->to){$flag = 1;}
            }
        }
        if($flag == 0){
            $save_group = material_group::limit(1)->where(['id'=>$request->id])->update([
                'name'        =>$request->name,
                'start_serial'=>$request->from,
                'end_serial'  =>$request->to,
                'main_group'  =>$request->main_group,
            ]);
            $status ='true';
            $msg = 'تم تعديل المجموعه';
        }else{
            $status ='false';
            $msg = 'هذا الترقيم يتعارض مع مجموعه اخري';
        }
        $new_group = $this->getNextUserId();
        return response()->json(['status'=>$status,'msg'=>$msg,'new_group'=>$new_group]);
    }

}

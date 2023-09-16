<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainGroupRequest;
use App\Models\MainGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainGroupController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    private function getNextId()
    {
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'main_groups'");
        $nextUserId = $statement[0]->Auto_increment;
        return $nextUserId;
    }
    public function view_groups(){
        $new_id = $this->getNextId();
        $groups = MainGroup::get()->all();
        return view('stock.stock.mainGroup',compact('new_id','groups'));
    }
    public function save_groups(MainGroupRequest $request){
        $save = MainGroup::create(['name'=>$request->name]);
        if($save){return response()->json(['status'=>'true','msg'=>'تم حفظ المجموعة','new_group'=>$this->getNextId()]);}
    }
    public function search_groups(Request $request){
        $data = MainGroup::where('name','like','%'.$request['query'].'%')->get();
        if($data){return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$data]);}
    }
    public function get_groups(Request $request){
        $data = MainGroup::limit(1)->where('id',$request->id)->first();
        return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$data]);
    }
    public function update_groups(MainGroupRequest $request){
        $update = MainGroup::limit(1)->where(['id'=>$request->id])->update(['name'=>$request->name]);
        if($update){return response()->json(['status'=>'true','msg'=>'تم تعديل المجموعة','new_group'=>$this->getNextId()]);}
    }

}

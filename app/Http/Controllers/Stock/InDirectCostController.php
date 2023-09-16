<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\InDirectRequest;
use App\Models\InDirectCost;
use App\Models\InDirectCostDaily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class InDirectCostController extends Controller
{
    public function index(){
        $data = InDirectCost::get()->all();
        $date = Date("Y-m-d");
        $month = date('m', strtotime($date));
        $inDirectCosts = InDirectCostDaily::where('month',$month)->get();
        return view('stock.stock.indirect_cost',compact('data','inDirectCosts'));
    }
    public function save(InDirectRequest $request){
        $save = InDirectCost::create([
            'name'=>$request->name,
        ]);
        if($save){
            return response()->json(['status'=>true,'msg'=>'تم حفظ البيانات','id'=>$save->id]);
        }
    }
    public function update(InDirectRequest $request){
        $update = InDirectCost::find($request->id);
        $update->name = $request->name;
        $update->save();
        if($update){
            return response()->json(['status'=>true,'msg'=>'تم تعديل البيانات']);
        }
    }
    public function destroy(Request $request){
        $del = InDirectCost::find($request->id);
        if($del){
            $del->delete();
            return response()->json(['status'=>true,'msg'=>'تم حذف البيانات']);
        }
    }

    public function saveInDirectValue(Request $request){
        $month = date('m', strtotime($request->date));
        $save = InDirectCostDaily::create([
            'cost_id'=>$request->inDirectCost,
            'value'=>$request->value,
            'user_id'=>Auth::user()->id,
            'date'=>$request->date,
            'month'=>$month,
        ]);
        if($save){
            return response()->json(['status'=>true,'msg'=>'تم حفظ البيانات','id'=>$save->id]);
        }
    }
    public function deleteInDirectValue(Request $request){
        $del = InDirectCostDaily::find($request->id);
        if($del){
            $del->delete();
            return response()->json(['status'=>true,'msg'=>'تم حذف البيانات']);
        }
    }
}

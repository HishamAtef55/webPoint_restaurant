<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Stores;
use App\Traits\MainFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Suppliers;

class SuppliersController extends Controller
{
    use MainFunction;
    public function __construct()
    {
        $this->middleware('auth');
    }
    private function getNextUserId()
    {
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'suppliers'");
        $nextUserId = $statement[0]->Auto_increment;
        return $nextUserId;
    }
    public function view_suppliers(){
        $new_supplier = $this->getNextUserId();
        $supplires = Suppliers::get()->all();
        return view('stock.stock.suppliers',compact('new_supplier','supplires'));
    }
    public function save_suppliers(SupplierRequest $request){
        $save =Suppliers::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ]);
        $new_supplier = $this->getNextUserId();
        if($save){return response()->json(['status'=>'true','new_supplier'=>$new_supplier,'msg'=>'تم حفظ المورد بنجاح']);}
    }
    public function search_suppliers(Request $request){
        $query =  $request['query'];
        $stores = Suppliers::where('name', 'LIKE', '%' . $query . "%")->select(['id','name'])->get();
        return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$stores]);
    }
    public function get_suppliers(Request $request){
        $supplier = Suppliers::limit(1)->where(['id'=>$request->id])->first();
        return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$supplier]);
    }
    public function update_suppliers(Request $request){
        if($request->name != null){
            $save =Suppliers::where(['id'=>$request->id])->update([
                'name'=>$request->name,
                'phone'=>$request->phone,
                'address'=>$request->address,
            ]);
            $new_supplier = $this->getNextUserId();
            if($save){return response()->json(['status'=>'true','new_supplier'=>$new_supplier,'msg'=>'تم تعديل المورد بنجاح']);}
        }
    }
}

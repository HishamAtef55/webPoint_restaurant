<?php

namespace App\Http\Controllers\Stock;
use App\Http\Controllers\Controller;
use App\Models\material;
use App\Models\storeCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Stores;
use App\Models\storage_capacity;
use App\Http\Requests\StoreRequest;
use App\Traits\MainFunction;
use Spatie\Permission\Models\Permission;

class StockController extends Controller
{
    use MainFunction;
    public function __construct(){
        $this->middleware('auth');
    }
    private function getNextUserId()
    {
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'stores'");
        $nextUserId = $statement[0]->Auto_increment;
        return $nextUserId;
    }
    public function view_Store(){
        $new_store = $this->getNextUserId();
        $stores = Stores::get()->all();
        return view('stock.stock.stores',compact(['new_store','stores']));
    }
    public function save_store(StoreRequest $request){
        $save_store = Stores::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ]);
        if($save_store){
            $permission = "stocks-".$request->name;
            Permission::create([
                'name'=>$permission,
                'type'=>'stock'
            ]);
            foreach($request->storages as $method_cap){
                $save_methods = storage_capacity::create([
                    'store' =>$save_store->id,
                    'type' =>$method_cap['type'],
                    'unit' =>$method_cap['unit'],
                    'capacity' =>$method_cap['capacity'],
                ]);
            }
            foreach (material::select(['code','name','unit'])->get() as $material){
                storeCost::create([
                    'store_id' =>$save_store->id,
                    'code' =>$material->code,
                    'material' =>$material->name,
                    'unit' =>$material->unit,
                ]);
            }
            $new_store = $this->getNextUserId();
            return response()->json(['status'=>'true','msg'=>'تم حفظ البيانات','new_store'=>$new_store]);
        }
    }
    public function search_store(Request $request){
        $query =  $request['query'];
        $stores = Stores::where('name', 'LIKE', '%' . $query . "%")->select(['id','name'])->get();
        return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$stores]);

    }
    public function get_store(Request $request){
        $store = Stores::limit(1)->with('storgecapacity')->where(['id'=>$request->id])->first();
        if($store){
            return response()->json(['status'=>'true','msg'=>'All Data For Get','data'=>$store]);
        }
    }
    public function update_store(Request $request){
        $get_store = Stores::where(['id'=>$request->id])->first();
        $permission = "stocks-".$get_store->name;
        $updatePermission = Permission::whereName($permission)->first();
        $save_store = Stores::where(['id'=>$request->id])->update([
            'name'    =>$request->name,
            'phone'   =>$request->phone,
            'address' =>$request->address,
        ]);
        if($save_store){
            $updatePermission->name = "stocks-".$request->name;
            $updatePermission->save();
            $del = storage_capacity::where(['store'=>$request->id,])->delete();
            foreach($request->storages as $method_cap){
                $save_methods = storage_capacity::create([
                    'store'    =>$request->id,
                    'type'     =>$method_cap['type'],
                    'unit'     =>$method_cap['unit'],
                    'capacity' =>$method_cap['capacity'],
                ]);
            }
            $new_store = $this->getNextUserId();
            return response()->json(['status'=>'true','msg'=>'تم تعديل البيانات','new_store'=>$new_store]);
        }
    }
}

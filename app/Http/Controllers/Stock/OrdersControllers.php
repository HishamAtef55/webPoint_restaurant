<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\stockOrders;
use App\Models\stockOrdersDetails;
use App\Models\Stores;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersControllers extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    protected function getSerial($type){
        return  stockOrders::max('id')+1;
    }

    public function index(){
        $serial = $this->getSerial('store');
        $stores    = Stores::get()->all();
        $branches = Branch::get()->all();
        return view('stock.stock.orders',compact(['serial','stores','branches']));
    }

    public function save(Request $request){
        $img = null;
        if($request->hasFile('image')){
            $img = saveImage($request->image,'stock/images/orders');
        }
        if($request->branch == null || $request->branch == "null"){$request->branch = 0;}
        $saveMain = stockOrders::create([
            'date'=>$request->date,
            'type'=>$request->type,
            'sto_sec_id'=>$request->stores ?? $request->sections,
            'branch_id'=>$request->branch,
            'user_id'=>Auth::user()->id,
            'total'=>$request->sumTotal,
            'img'=>$img,
            'note'=>$request->notes,
        ]);
        if($saveMain){
            $data = json_decode($request->materialArray);
            foreach ($data as $row) {
                $saveDeatils = stockOrdersDetails::create([
                    'order_id' => $saveMain->id,
                    'code' => $row->code,
                    'name' => $row->itemName,
                    'unit' => $row->unitName,
                    'qty' => $row->quantity,
                    'price' => $row->priceUnit,
                ]);
            }
        }
        return ['status'=>true,'data'=>'تم اضافة الطلبية بنجاح','id'=>$this->getSerial('store')];
    }

    public function getData(Request $request){
        $data = stockOrders::limit(1)->with('details')->where(['id'=>$request->permission])->first();
        return ['status'=>true,'data'=>$data];
    }

    public function update(Request $request){
        $order = stockOrders::find($request->permission);
        if($order){
            $data = json_decode($request->materialArray);
            foreach ($data as $row) {
                if (stockOrdersDetails::whereOrderId($request->permission)->whereCode($row->code)->count() == 0) {
                    $saveDeatils = stockOrdersDetails::create([
                        'order_id' => $request->permission,
                        'code' => $row->code,
                        'name' => $row->itemName,
                        'unit' => $row->unitName,
                        'qty' => $row->quantity,
                        'price' => $row->priceUnit,
                    ]);
                }
            }
            $order->total = $request->sumTotal;
            $order->save();
        }
        return ['status'=>true,'data'=>'تم التعديل علي الطلبية بنجاح','id'=>$this->getSerial('store')];
    }

    public function destroy(Request $request){
        $order = stockOrders::onlyTrashed()->findOrFail($request->permission);
        $order->forceDelete();
        return ['status'=>true,'data'=>'تم حذف الطلبية بنجاح','id'=>$this->getSerial('store')];

    }
}

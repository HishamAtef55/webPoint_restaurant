<?php

namespace App\Http\Controllers\Stock;

use App\Models\Branch;
use App\Models\Stores;
use App\Models\storeCost;
use App\Models\Suppliers;
use App\Models\stock_unit;
use App\Models\materialLog;
use App\Models\sectionCost;
use App\Models\Stock\Store;
use App\Models\exchangeMain;
use Illuminate\Http\Request;
use App\Models\backToStoreMain;
use App\Models\exchangeDetails;
use App\Models\backToStoreDetails;
use Illuminate\Support\Facades\DB;
use App\Models\backToSuppliersMain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\backToSuppliersDetails;

class BackToStoresControllers extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function getSerial(){
        $serial = 0;
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'stock_back_to_store_mains'");
        $serial = $statement[0]->Auto_increment;
        return $serial;
    }
    public function index(){
        $serial = $this->getSerial();
        $stores    = Store::get()->all();
        $branches = Branch::get()->all();
        return view('stock.stock.back_to_stores',compact(['serial','stores','branches']));
    }
    protected function materialLog($data){
        materialLog::create($data);
    }
    public function save(Request $request){
        $data = json_decode($request->materialArray);
        $image = '';
        if($request->image != 'undefined'){
            $image = $this->storeImage($request->image,'stock/images/exchanges');
        }
        $saveMain = backToStoreMain::create([
            'serial_id'=>$request->seriesNumber,
            'order_id'=>$request->orderNumber,
            'note'=>$request->notes,
            'store_id'=>$request->stores,
            'branch_id'=>$request->branch,
            'section_id'=>$request->sections,
            'date'=>$request->date,
            'user_id'=>Auth::user()->id,
            'image'=>$image,
            'total'=>$request->sumFinal,
        ]);
        if($saveMain){
            foreach ($data as $row) {
                $saveDeatils = backToStoreDetails::create([
                    'order_id' => $saveMain->id,
                    'code' => $row->code,
                    'name' => $row->itemName,
                    'unit' => $row->unitName,
                    'qty' => $row->quantity,
                    'price' => $row->priceUnit,
                    'total' => $row->finalTotal,
                ]);
                $updateCost = sectionCost::limit(1)->where(['section_id'=>$request->sections,'code'=>$row->code])->first();
                $realPrice = $row->priceUnit;
                $realQty = 0;
                if($row->unitName == $updateCost->unit){
                    if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                    $updateCost->l_price = $realPrice;
                    $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                    $updateCost->average = $avaPrice;
                    $updateCost->qty -= $row->quantity;
                    $realQty = $row->quantity;
                }else{
                    $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                    $qtyUnit = $row->quantity / $unitQty->size;
                    $priceUnit = $realPrice * $unitQty->size;
                    if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                    $updateCost->l_price = $priceUnit;
                    $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                    $updateCost->average = $avaPrice;
                    $updateCost->qty -= $qtyUnit;
                    $realQty = $qtyUnit;
                }
                $updateCost->save();
                $updateStore = storeCost::limit(1)->where(['store_id'=>$request->stores,'code'=>$row->code])->first();
                $updateStore->qty += $realQty;
                $updateStore->save();
                $materialLog = [
                    'user'=>Auth::user()->id,
                    'code'=>$row->code,
                    'type'=>'اذن مرتجع من مخزن',
                    'section'=>$request->sections,
                    'from' =>'store ' . $request->stores,
                    'order_id'=>$saveMain->id,
                    'to' => branchName($request->branch) .  ' | '  . sectionName($request->sections),
                    'store' =>$request->stores
                ];
                $this->materialLog($materialLog);
            }
        }
        return ['status'=>true,'data'=>'تم حفظ الاذن بنجاح'];
    }
    public function get(Request $request){
        $data = [];
        $data = backToStoreMain::limit(1)->with('details')->where(['id'=>$request->permission])->first();
        return ['status'=>true,'data'=>$data];
    }
    public function getViaSerial(Request $request){
        $data = [];
        $data = backToStoreMain::limit(1)->with('details')->where(['serial_id'=>$request->serial])->first();
        return ['status'=>true,'data'=>$data];
    }
    public function delete(Request $request){
        $flag = false;
        $getExchange = backToStoreMain::find($request->permission);
        $getExchangeDetails = backToStoreDetails::where(['order_id'=>$request->permission])->get();
        foreach ($getExchangeDetails as $row){
            $updateQty = sectionCost::limit(1)->where(['section_id'=>$getExchange->section_id,'code'=>$row->code])->first();
            $updateQty->qty += $row->qty;
            $updateQty->save();
            $deleteDetails = backToStoreDetails::find($row->id);
            $deleteDetails->delete();
            $updateStore = storeCost::limit(1)->where(['store_id'=>$getExchange->store_id,'code'=>$row->code])->first();
            $updateStore->qty -= $row->qty;
            $updateStore->save();
        }
        if($getExchangeDetails){
            $getExchange->delete();
            $flag = true;
        }
        if($flag){return['status'=>true,'data'=>'تم حذف الاذن بنجاح'];}
    }
    public function update(Request $request){
        $flag = false;
        $data = json_decode($request->materialArray);
        $updateExchange = backToStoreMain::find($request->permission);
        if($updateExchange){
            foreach ($data as $row) {
                $saveDeatils = backToStoreDetails::create([
                    'order_id' => $updateExchange->id,
                    'code' => $row->code,
                    'name' => $row->itemName,
                    'unit' => $row->unitName,
                    'qty' => $row->quantity,
                    'price' => $row->priceUnit,
                    'total' => $row->finalTotal,
                ]);
                $updateCost = sectionCost::limit(1)->where(['section_id'=>$request->sections,'code'=>$row->code])->first();
                $realPrice = $row->priceUnit;
                $realQty = 0;
                if($row->unitName == $updateCost->unit){
                    if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                    $updateCost->l_price = $realPrice;
                    $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                    $updateCost->average = $avaPrice;
                    $updateCost->qty -= $row->quantity;
                    $realQty = $row->quantity;
                }else{
                    $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                    $qtyUnit = $row->quantity / $unitQty->size;
                    $priceUnit = $realPrice * $unitQty->size;
                    if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                    $updateCost->l_price = $priceUnit;
                    $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                    $updateCost->average = $avaPrice;
                    $updateCost->qty -= $qtyUnit;
                    $realQty =  $qtyUnit;
                }
                $updateCost->save();
                $updateStore = storeCost::limit(1)->where(['store_id'=>$request->stores,'code'=>$row->code])->first();
                $updateStore->qty += $realQty;
                $updateStore->save();
                $materialLog = [
                    'user'=>Auth::user()->id,
                    'code'=>$row->code,
                    'type'=>'اذن مرتجع من مخزن',
                    'section'=>$request->sections,
                    'from' =>'store ' . $request->stores,
                    'order_id'=>$updateExchange->id,
                    'to' => branchName($request->branch) .  ' | '  . sectionName($request->sections),
                    'store' =>$request->stores
                ];
                $this->materialLog($materialLog);
            }
            $updateExchange->total = $request->sumFinal;
            $updateExchange->save();
            $flag = true;
        }
        if($flag){return['status'=>true,'data'=>'تم التعديل بنجاح'];}
    }
    public function deleteItem(Request $request){
        $flag = false;
        $row  = backToStoreDetails::limit(1)->where(['id'=>$request->rowId])->first();
        $oldQty = $row->qty;
        $row->delete();
        if($row){
            $updateExchange = backToStoreMain::find($request->permission);
            $updateExchange->total=$request->sumFinal;
            $updateExchange->save();
            if($updateExchange){
                $updateCost = sectionCost::limit(1)->where(['section_id'=>$updateExchange->section_id,'code'=>$request->code])->first();
                $updateCost->qty += $oldQty;
                $updateCost->save();
                if($updateCost){
                    $updateStore = storeCost::limit(1)->where(['store_id'=>$updateExchange->store_id,'code'=>$row->code])->first();
                    $updateStore->qty -= $oldQty;
                    $updateStore->save();
                    $flag = true;
                }
            }
        }
        if($flag){return['status'=>true,'data'=>'تم الحذف بنجاح'];}
    }
    public function updateItem(Request $request){
        $flag = false;
        $row  = backToStoreDetails::limit(1)->where(['id'=>$request->rowId])->first();
        $oldQty = $row->qty;
        $row->price = $request->priceUnit;
        $row->qty = $request->quantity;
        $row->total = $request->finalTotal;
        $row->save();
        if($row){
            $updateExchange = backToStoreMain::find($request->permission);
            $updateExchange->total=$request->sumFinal;
            $updateExchange->save();
            if($updateExchange){
                $updateCost = sectionCost::limit(1)->where(['section_id'=>$updateExchange->section_id,'code'=>$request->code])->first();
                $updateCost->qty += $oldQty;
                $updateCost->qty -=$request->quantity;
                $updateCost->save();
                if($updateCost){
                    $updateStore = storeCost::limit(1)->where(['store_id'=>$updateExchange->store_id,'code'=>$row->code])->first();
                    $updateStore->qty -= $oldQty;
                    $updateStore->qty += $request->quantity;;
                    $updateStore->save();
                    $flag = true;
                }
            }
        }
        if($flag){return['status'=>true,'data'=>'تم التعديل بنجاح'];}
    }
}

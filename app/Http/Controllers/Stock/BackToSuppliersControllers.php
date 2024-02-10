<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\backToSuppliersDetails;
use App\Models\backToSuppliersMain;
use App\Models\Branch;
use App\Models\halkDetails;
use App\Models\halkMain;
use App\Models\materialLog;
use App\Models\sectionCost;
use App\Models\stock_unit;
use App\Models\storeCost;
use App\Models\Stores;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BackToSuppliersControllers extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function getSerial(){
        $serial = 0;
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'stock_back_to_suppliers_mains'");
        $serial = $statement[0]->Auto_increment;
        return $serial;
    }

    public function index(){
        $serial = $this->getSerial();
        $stores    = Stores::get()->all();
        $branches = Branch::get()->all();
        $suppliers = Suppliers::get()->all();
        return view('stock.stock.back_to_suppliers',compact(['serial','stores','branches','suppliers']));
    }
    protected function materialLog($data){
        materialLog::create($data);
    }
    public function save(Request $request){
        $data = json_decode($request->materialArray);
        $image = '';
        if($request->image != 'undefined'){
            $image = $this->storeImage($request->image,'stock/images/back_to_suppliers');
        }
        $from = 0;
        $to   = 0;
        if($request->type == 'store'){
            $from = $request->stores;
            $fromName = storeName($from);
        }elseif ($request->type == 'section'){
            $from = $request->sections;
            $fromName = sectionName($from);
        }
        if($request->branch == 'null'){$request->branch = 0;}
        $saveMain = backToSuppliersMain::create([
            'serial_id'=>$request->seriesNumber,
            'note'=>$request->notes,
            'type'=>$request->type,
            'supplier'=>$request->supplier,
            'from'=>$from,
            'branch_id'=>$request->branch,
            'date'=>$request->date,
            'user_id'=>Auth::user()->id,
            'image'=>$image,
            'total'=>$request->sumFinal,
        ]);
        if($saveMain){
            foreach ($data as $row) {
                $saveDeatils = backToSuppliersDetails::create([
                    'order_id' => $saveMain->id,
                    'code' => $row->code,
                    'name' => $row->itemName,
                    'unit' => $row->unitName,
                    'qty' => $row->quantity,
                    'price' => $row->priceUnit,
                    'total' => $row->finalTotal,
                ]);
                if($request->type == 'store'){
                    $updateCost = storeCost::limit(1)->where(['store_id'=>$from,'code'=>$row->code])->first();
                    $realPrice = $row->priceUnit;
                    if($row->unitName == $updateCost->unit){
                        if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                        $updateCost->l_price = $realPrice;
                        $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                        $updateCost->average = $avaPrice;
                        $updateCost->qty -= $row->quantity;
                    }else{
                        $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                        $qtyUnit = $row->quantity / $unitQty->size;
                        $priceUnit = $realPrice * $unitQty->size;
                        if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                        $updateCost->l_price = $priceUnit;
                        $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                        $updateCost->average = $avaPrice;
                        $updateCost->qty -= $qtyUnit;
                    }
                    $updateCost->save();
                }elseif ($request->type == 'section'){
                    $updateCost = sectionCost::limit(1)->where(['section_id'=>$from,'code'=>$row->code])->first();
                    $realPrice = $row->priceUnit;
                    if($row->unitName == $updateCost->unit){
                        if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                        $updateCost->l_price = $realPrice;
                        $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                        $updateCost->average = $avaPrice;
                        $updateCost->qty -= $row->quantity;
                    }else{
                        $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                        $qtyUnit = $row->quantity / $unitQty->size;
                        $priceUnit = $realPrice * $unitQty->size;
                        if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                        $updateCost->l_price = $priceUnit;
                        $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                        $updateCost->average = $avaPrice;
                        $updateCost->qty -= $qtyUnit;
                    }
                    $updateCost->save();
                }
                $materialLog = [
                    'user'=>Auth::user()->id,
                    'code'=>$row->code,
                    'type'=>'اذن مرتجع',
                    'section'=>5,
                    'order_id'=>$saveMain->id,
                    'from' =>$fromName,
                    'store' =>$request->stores
                ];
            }
            $this->materialLog($materialLog);
            return ['status'=>true,'data'=>'تم الحفظ بنجاح','id'=>$this->getSerial()];
        }
    }
    public function get(Request $request){
        $data = [];
        $data = backToSuppliersMain::limit(1)->with('details')->where(['id'=>$request->permission])->first();
        return ['status'=>true,'data'=>$data];
    }
    public function getViaSerial(Request $request){
        $data = [];
        $data = backToSuppliersMain::limit(1)->with('details')->where(['serial_id'=>$request->serial])->first();
        return ['status'=>true,'data'=>$data];
    }
    public function update(Request $request){
        $data = json_decode($request->materialArray);
        $image = '';
        if($request->image != 'undefined'){
            $image = $this->storeImage($request->image,'stock/images/transfers');
        }
        $from = 0;
        $to   = 0;
        if($request->type == 'store'){
            $from = $request->stores;
            $fromName = storeName($from);
        }elseif ($request->type == 'section'){
            $from = $request->sections;
            $fromName = sectionName($from);
        }
        if($request->branch == 'null'){$request->branch = 0;}
        $saveMain = backToSuppliersMain::find($request->permission);
        $saveMain->serial_id = $request->seriesNumber;
        $saveMain->note = $request->notes;
        $saveMain->type = $request->type;
        $saveMain->supplier = $request->supplier;
        $saveMain->total = $request->sumFinal;
        $saveMain->save();
        if($saveMain){
            foreach ($data as $row) {
                $saveDeatils = backToSuppliersDetails::create([
                    'order_id' => $saveMain->id,
                    'code' => $row->code,
                    'name' => $row->itemName,
                    'unit' => $row->unitName,
                    'qty' => $row->quantity,
                    'price' => $row->priceUnit,
                    'total' => $row->finalTotal,
                ]);
                if($request->type == 'store'){
                    $updateCost = storeCost::limit(1)->where(['store_id'=>$from,'code'=>$row->code])->first();
                    $realPrice = $row->priceUnit;
                    if($row->unitName == $updateCost->unit){
                        if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                        $updateCost->l_price = $realPrice;
                        $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                        $updateCost->average = $avaPrice;
                        $updateCost->qty -= $row->quantity;
                    }else{
                        $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                        $qtyUnit = $row->quantity / $unitQty->size;
                        $priceUnit = $realPrice * $unitQty->size;
                        if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                        $updateCost->l_price = $priceUnit;
                        $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                        $updateCost->average = $avaPrice;
                        $updateCost->qty -= $qtyUnit;
                    }
                    $updateCost->save();
                }elseif ($request->type == 'section'){
                    $updateCost = sectionCost::limit(1)->where(['section_id'=>$from,'code'=>$row->code])->first();
                    $realPrice = $row->priceUnit;
                    if($row->unitName == $updateCost->unit){
                        if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                        $updateCost->l_price = $realPrice;
                        $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                        $updateCost->average = $avaPrice;
                        $updateCost->qty -= $row->quantity;
                    }else{
                        $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                        $qtyUnit = $row->quantity / $unitQty->size;
                        $priceUnit = $realPrice * $unitQty->size;
                        if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                        $updateCost->l_price = $priceUnit;
                        $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                        $updateCost->average = $avaPrice;
                        $updateCost->qty -= $qtyUnit;
                    }
                    $updateCost->save();
                }
                $materialLog = [
                    'user'=>Auth::user()->id,
                    'code'=>$row->code,
                    'type'=>'اذن هالك',
                    'section'=>5,
                    'order_id'=>$saveMain->id,
                    'from' =>$fromName,
                    'store' =>$request->stores
                ];
            }
            $this->materialLog($materialLog);
            return ['status'=>true,'data'=>'تم الحفظ بنجاح','id'=>$this->getSerial()];
        }
    }
    public function updateItem(Request $request){
        $getRow = backToSuppliersDetails::find($request->rowId);
        $oldQty = $getRow->qty;
        $getRow->price = $request->priceUnit;
        $getRow->qty = $request->quantity;
        $getRow->total = $request->finalTotal;
        $getRow->save();
        $getMain = backToSuppliersMain::find($request->permission);
        $getMain->total = $request->sumFinal;
        $getMain->save();
        if($getMain->type == 'store'){
            $updateFromCost = storeCost::limit(1)->where(['store_id'=>$getMain->from,'code'=>$request->code])->first();;
            $updateFromCost->qty += $oldQty;
            $updateFromCost->qty -= $request->quantity;
            $updateFromCost->save();
        }elseif ($getMain->type == 'section'){
            $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$getMain->from,'code'=>$request->code])->first();;
            $updateFromCost->qty += $oldQty;
            $updateFromCost->qty -= $request->quantity;
            $updateFromCost->save();
        }
        return ['status'=>true,'data'=>'تم التعديل بنجاح',];
    }
    public function deleteItem(Request $request){
        $getRow = backToSuppliersDetails::find($request->rowId);
        $oldQty = $getRow->qty;
        $getRow->delete();
        $getMain = backToSuppliersMain::find($request->permission);
        $getMain->total = $request->sumFinal;
        $getMain->save();
        if($getMain->type == 'store'){
            $updateFromCost = storeCost::limit(1)->where(['store_id'=>$getMain->from,'code'=>$request->code])->first();;
            $updateFromCost->qty += $oldQty;
            $updateFromCost->save();
        }elseif ($getMain->type == 'section'){
            $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$getMain->from,'code'=>$request->code])->first();;
            $updateFromCost->qty += $oldQty;
            $updateFromCost->save();
        }
        return ['status'=>true,'data'=>'تم الحذف بنجاح',];
    }
    public function delete(Request $request){
        $details = backToSuppliersDetails::where(['order_id'=>$request->permission])->get();
        $getMain = backToSuppliersMain::find($request->permission);
        foreach ($details as $detail){
            if($getMain->type == 'store'){
                $updateFromCost = storeCost::limit(1)->where(['store_id'=>$getMain->from,'code'=>$detail->code])->first();;
                $updateFromCost->qty += $detail->qty;
                $updateFromCost->save();
            }elseif ($getMain->type == 'section'){
                $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$getMain->from,'code'=>$detail->code])->first();;
                $updateFromCost->qty += $detail->qty;
                $updateFromCost->save();
            }
            $delDetails = backToSuppliersDetails::find($detail->id)->delete();
        }
        $getMain->delete();
        if($getMain){
            return ['status'=>true,'data'=>'تم الحذف بنجاح'];
        }
    }
}

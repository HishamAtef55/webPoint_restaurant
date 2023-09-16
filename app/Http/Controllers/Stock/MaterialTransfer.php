<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\materialLog;
use App\Models\sectionCost;
use App\Models\stock_unit;
use App\Models\storeCost;
use App\Models\Stores;
use App\Models\Suppliers;
use App\Models\transfersDetails;
use App\Models\transfersMain;
use App\Traits\MainFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialTransfer extends Controller
{
    use MainFunction;
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function getSerial(){
        $serial = 0;
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'transfers_mains'");
        $serial = $statement[0]->Auto_increment;
        return $serial;
    }
    public function index(){
        $serial = $this->getSerial();
        $stores    = Stores::get()->all();
        $branches = Branch::get()->all();
        $suppliers = Suppliers::get()->all();
        return view('stock.stock.transfers',compact(['serial','stores','branches','suppliers']));
    }
    public function changeType(Request $request){
        return ['serial'=>$this->getSerial($request->type)];
    }
    protected function materialLog($data){
        materialLog::create($data);
    }
    public function save(Request $request){
        $data = json_decode($request->materialArray);
        $image = '';
        if($request->image != 'undefined'){
            $image = $this->storeImage($request->image,'stock/images/transfers');
        }
        $from = 0;
        $to   = 0;
        if($request->type == 'store'){
            $from = $request->fromStore;
            $fromName = storeName($from);
            $to = $request->toStore;
            $toName = storeName($to);
        }elseif ($request->type == 'section'){
            $from = $request->fromSection;
            $fromName = sectionName($from);
            $to = $request->toSection;
            $toName = sectionName($to);
        }
        if($request->branch == 'null'){$request->branch = 0;}
        $check = $this->checkMaterial($data,$request->type,$to);
        if($check == 'false'){
            return ['status'=>false,'msg'=>'هذة الخامة غير تابعه'];
        }elseif($check == 'true'){
            $saveMain = transfersMain::create([
                'serial_id'=>$request->seriesNumber,
                'note'=>$request->notes,
                'type'=>$request->type,
                'from'=>$from,
                'to'=>$to,
                'branch_id'=>$request->branch,
                'date'=>$request->date,
                'user_id'=>Auth::user()->id,
                'image'=>$image,
                'total'=>$request->sumFinal,
            ]);
            if($saveMain){
                foreach ($data as $row) {
                    $saveDeatils = transfersDetails::create([
                        'order_id' => $saveMain->id,
                        'code' => $row->code,
                        'name' => $row->itemName,
                        'unit' => $row->unitName,
                        'qty' => $row->quantity,
                        'price' => $row->priceUnit,
                        'total' => $row->finalTotal,
                    ]);
                    if($request->type == 'store'){
                        $updateCost = storeCost::limit(1)->where(['store_id'=>$to,'code'=>$row->code])->first();
                        $realPrice = $row->priceUnit;
                        if($row->unitName == $updateCost->unit){
                            if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                            $updateCost->l_price = $realPrice;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $row->quantity;
                        }else{
                            $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                            $qtyUnit = $row->quantity / $unitQty->size;
                            $priceUnit = $realPrice * $unitQty->size;
                            if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                            $updateCost->l_price = $priceUnit;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $qtyUnit;
                        }
                        $updateCost->save();
                        $updateCostFrom = storeCost::limit(1)->where(['store_id'=>$from,'code'=>$row->code])->first();
                        $updateCostFrom->qty -= $row->quantity;
                        $updateCostFrom->save();
                    }elseif ($request->type == 'section'){
                        $updateCost = sectionCost::limit(1)->where(['section_id'=>$request->toSection,'code'=>$row->code])->first();
                        $realPrice = $row->priceUnit;
                        if($row->unitName == $updateCost->unit){
                            if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                            $updateCost->l_price = $realPrice;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $row->quantity;
                        }else{
                            $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                            $qtyUnit = $row->quantity / $unitQty->size;
                            $priceUnit = $realPrice * $unitQty->size;
                            if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                            $updateCost->l_price = $priceUnit;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $qtyUnit;
                        }
                        $updateCost->save();
                        $updateCostFrom = sectionCost::limit(1)->where(['section_id'=>$request->fromSection,'code'=>$row->code])->first();
                        $updateCostFrom->qty -= $row->quantity;
                        $updateCostFrom->save();
                    }
                    $materialLog = [
                        'user'=>Auth::user()->id,
                        'code'=>$row->code,
                        'type'=>'اذن تويل',
                        'section'=>5,
                        'from' =>$fromName,
                        'to' =>$toName,
                        'store' =>$request->stores
                    ];
                }
                $this->materialLog($materialLog);
                return ['status'=>true,'data'=>'تم التحويل بنجاح','id'=>$this->getSerial()];
            }
        }
    }
    protected function checkMaterial($materials , $type , $to){
        $flag = 'true';
        foreach ($materials as $material){
            if($type == 'store'){
                if(storeCost::where(['store_id'=>$to,'code'=>$material->code])->count() == 0){
                    $flag  = 'false';
                }
            }else{
                if(sectionCost::where(['section_id'=>$to,'code'=>$material->code])->count() == 0){
                    $flag  = 'false';
                }
            }
        }
        return $flag;
    }
    public function getTransfer(Request $request){
        $data = [];
        $data = transfersMain::limit(1)->with('details')->where(['id'=>$request->permission])->first();
        return ['status'=>true,'data'=>$data];
    }
    public function getTransferViaSerial(Request $request){
        $data = [];
        $data = transfersMain::limit(1)->with('details')->where(['serial_id'=>$request->serial])->first();
        return ['status'=>true,'data'=>$data];
    }
    public function updateTransfer(Request $request){
        $data = json_decode($request->materialArray);
        $image = '';
        if($request->image != 'undefined'){
            $image = $this->storeImage($request->image,'stock/images/transfers');
        }
        $from = 0;
        $to   = 0;
        if($request->type == 'store'){
            $from = $request->fromStore;
            $fromName = storeName($from);
            $to = $request->toStore;
            $toName = storeName($to);
        }elseif ($request->type == 'section'){
            $from = $request->fromSection;
            $fromName = sectionName($from);
            $to = $request->toSection;
            $toName = sectionName($to);
        }
        if($request->branch == 'null'){$request->branch = 0;}
        $check = $this->checkMaterial($data,$request->type,$to);
        if($check == 'false'){
            return ['status'=>false,'msg'=>'هذة الخامة غير تابعه'];
        }elseif($check == 'true'){
            $saveMain = transfersMain::find($request->permission);
            $saveMain->serial_id = $request->seriesNumber;
            $saveMain->note = $request->notes;
            $saveMain->type = $request->type;
            $saveMain->image = $image;
            $saveMain->total = $request->sumFinal;
            $saveMain->save();
            if($saveMain){
                foreach ($data as $row) {
                    $saveDeatils = transfersDetails::create([
                        'order_id' => $saveMain->id,
                        'code' => $row->code,
                        'name' => $row->itemName,
                        'unit' => $row->unitName,
                        'qty' => $row->quantity,
                        'price' => $row->priceUnit,
                        'total' => $row->finalTotal,
                    ]);
                    if($request->type == 'store'){
                        $updateCost = storeCost::limit(1)->where(['store_id'=>$to,'code'=>$row->code])->first();
                        $realPrice = $row->priceUnit;
                        if($row->unitName == $updateCost->unit){
                            if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                            $updateCost->l_price = $realPrice;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $row->quantity;
                        }else{
                            $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                            $qtyUnit = $row->quantity / $unitQty->size;
                            $priceUnit = $realPrice * $unitQty->size;
                            if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                            $updateCost->l_price = $priceUnit;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $qtyUnit;
                        }
                        $updateCost->save();
                        $updateCostFrom = storeCost::limit(1)->where(['store_id'=>$from,'code'=>$row->code])->first();
                        $updateCostFrom->qty -= $row->quantity;
                        $updateCostFrom->save();
                    }elseif ($request->type == 'section'){
                        $updateCost = sectionCost::limit(1)->where(['section_id'=>$request->toSection,'code'=>$row->code])->first();
                        $realPrice = $row->priceUnit;
                        if($row->unitName == $updateCost->unit){
                            if($updateCost->f_price == 0){$updateCost->f_price = $realPrice;}
                            $updateCost->l_price = $realPrice;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($realPrice * $row->quantity)) / ($updateCost->qty + $row->quantity);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $row->quantity;
                        }else{
                            $unitQty = stock_unit::limit(1)->where(['name'=>$updateCost->unit])->select(['size'])->first();
                            $qtyUnit = $row->quantity / $unitQty->size;
                            $priceUnit = $realPrice * $unitQty->size;
                            if($updateCost->f_price == 0){$updateCost->f_price = $priceUnit;}
                            $updateCost->l_price = $priceUnit;
                            $avaPrice = (($updateCost->qty * $updateCost->average) + ($priceUnit * $qtyUnit)) / ($updateCost->qty + $qtyUnit);
                            $updateCost->average = $avaPrice;
                            $updateCost->qty += $qtyUnit;
                        }
                        $updateCost->save();
                        $updateCostFrom = sectionCost::limit(1)->where(['section_id'=>$request->fromSection,'code'=>$row->code])->first();
                        $updateCostFrom->qty -= $row->quantity;
                        $updateCostFrom->save();
                    }
                    $materialLog = [
                        'user'=>Auth::user()->id,
                        'code'=>$row->code,
                        'type'=>'اذن تويل',
                        'section'=>5,
                        'from' =>$fromName,
                        'to' =>$toName,
                        'store' =>$request->stores
                    ];
                }
                $this->materialLog($materialLog);
                return ['status'=>true,'data'=>'تم التحويل بنجاح','id'=>$this->getSerial()];
            }
        }
    }
    public function updateItemTransfer(Request $request){
        $getRow = transfersDetails::find($request->rowId);
        $oldQty = $getRow->qty;
        $getRow->price = $request->priceUnit;
        $getRow->qty = $request->quantity;
        $getRow->total = $request->finalTotal;
        $getRow->save();
        $getMain = transfersMain::find($request->permission);
        $getMain->total = $request->sumFinal;
        $getMain->save();
        if($getMain->type == 'store'){
            $updateFromCost = storeCost::limit(1)->where(['store_id'=>$getMain->from,'code'=>$request->code])->first();;
            $updateFromCost->qty += $oldQty;
            $updateFromCost->qty -= $request->quantity;
            $updateFromCost->save();
            $updateToCost = storeCost::limit(1)->where(['store_id'=>$getMain->to,'code'=>$request->code])->first();
            $updateToCost->qty -= $oldQty;
            $updateToCost->qty += $request->quantity;
            $updateToCost->save();
        }elseif ($getMain->type == 'section'){
            $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$getMain->from,'code'=>$request->code])->first();;
            $updateFromCost->qty += $oldQty;
            $updateFromCost->qty -= $request->quantity;
            $updateFromCost->save();
            $updateToCost = sectionCost::limit(1)->where(['section_id'=>$getMain->to,'code'=>$request->code])->first();
            $updateToCost->qty -= $oldQty;
            $updateToCost->qty += $request->quantity;
            $updateToCost->save();
        }
        return ['status'=>true,'data'=>'تم التعديل بنجاح',];
    }
    public function deleteItemTransfer(Request $request){
        $getRow = transfersDetails::find($request->rowId);
        $oldQty = $getRow->qty;
        $getRow->delete();
        $getMain = transfersMain::find($request->permission);
        $getMain->total = $request->sumFinal;
        $getMain->save();
        if($getMain->type == 'store'){
            $updateFromCost = storeCost::limit(1)->where(['store_id'=>$getMain->from,'code'=>$request->code])->first();;
            $updateFromCost->qty += $oldQty;
            $updateFromCost->save();
            $updateToCost = storeCost::limit(1)->where(['store_id'=>$getMain->to,'code'=>$request->code])->first();
            $updateToCost->qty -= $oldQty;
            $updateToCost->save();
        }elseif ($getMain->type == 'section'){
            $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$getMain->from,'code'=>$request->code])->first();;
            $updateFromCost->qty += $oldQty;
            $updateFromCost->save();
            $updateToCost = sectionCost::limit(1)->where(['section_id'=>$getMain->to,'code'=>$request->code])->first();
            $updateToCost->qty -= $oldQty;
            $updateToCost->save();
        }
        return ['status'=>true,'data'=>'تم الحذف بنجاح',];
    }
    public function deleteTransfer(Request $request){
        $details = transfersDetails::where(['order_id'=>$request->permission])->get();
        $getMain = transfersMain::find($request->permission);
        foreach ($details as $detail){
            if($getMain->type == 'store'){
                $updateFromCost = storeCost::limit(1)->where(['store_id'=>$getMain->from,'code'=>$detail->code])->first();;
                $updateFromCost->qty += $detail->qty;
                $updateFromCost->save();
                $updateToCost = storeCost::limit(1)->where(['store_id'=>$getMain->to,'code'=>$detail->code])->first();
                $updateToCost->qty -= $detail->qty;
                $updateToCost->save();
            }elseif ($getMain->type == 'section'){
                $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$getMain->from,'code'=>$detail->code])->first();;
                $updateFromCost->qty += $detail->qty;
                $updateFromCost->save();
                $updateToCost = sectionCost::limit(1)->where(['section_id'=>$getMain->to,'code'=>$detail->code])->first();
                $updateToCost->qty -= $detail->qty;
                $updateToCost->save();
            }
            $delDetails = transfersDetails::find($detail->id)->delete();
        }
        $getMain->delete();
        if($getMain){
            return ['status'=>true,'data'=>'تم الحذف بنجاح'];
        }
    }
}

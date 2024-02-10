<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\halkDetails;
use App\Models\HalkItem;
use App\Models\halkMain;
use App\Models\Item;
use App\Models\MainComponents;
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

class MaterialHalk extends Controller
{
    use MainFunction;
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function getSerial(){
        $serial = 0;
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'stock_halk_mains'");
        $serial = $statement[0]->Auto_increment;
        return $serial;
    }
    public function index(){
        $serial = $this->getSerial();
        $stores    = Stores::get()->all();
        $branches = Branch::get()->all();
        $suppliers = Suppliers::get()->all();
        return view('stock.stock.halk',compact(['serial','stores','branches','suppliers']));
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
        }elseif ($request->type == 'section'){
            $from = $request->fromSection;
            $fromName = sectionName($from);
        }
        if($request->branch == 'null'){$request->branch = 0;}
        $saveMain = halkMain::create([
            'serial_id'=>$request->seriesNumber,
            'note'=>$request->notes,
            'type'=>$request->type,
            'from'=>$from,
            'branch_id'=>$request->branch,
            'date'=>$request->date,
            'user_id'=>Auth::user()->id,
            'image'=>$image,
            'total'=>$request->sumFinal,
        ]);
        if($saveMain){
            foreach ($data as $row) {
                $saveDeatils = halkDetails::create([
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
                    'from' =>$fromName,
                    'store' =>$request->stores
                ];
            }
            $this->materialLog($materialLog);
            return ['status'=>true,'data'=>'تم التهليك بنجاح','id'=>$this->getSerial()];
        }
    }
    public function getHalk(Request $request){
        $data = [];
        $data = halkMain::limit(1)->with('details')->where(['id'=>$request->permission])->first();
        return ['status'=>true,'data'=>$data];
    }
    public function getHalkViaSerial(Request $request){
        $data = [];
        $data = halkMain::limit(1)->with('details')->where(['serial_id'=>$request->serial])->first();
        return ['status'=>true,'data'=>$data];
    }
    public function updateHalk(Request $request){
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
        }elseif ($request->type == 'section'){
            $from = $request->fromSection;
            $fromName = sectionName($from);
        }
        if($request->branch == 'null'){$request->branch = 0;}
        $saveMain = halkMain::find($request->permission);
        $saveMain->serial_id = $request->seriesNumber;
        $saveMain->note = $request->notes;
        $saveMain->type = $request->type;
        $saveMain->image = $image;
        $saveMain->total = $request->sumFinal;
        $saveMain->save();
        if($saveMain){
            foreach ($data as $row) {
                $saveDeatils = halkDetails::create([
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
                    'from' =>$fromName,
                    'store' =>$request->stores
                ];
            }
            $this->materialLog($materialLog);
            return ['status'=>true,'data'=>'تم التهليك بنجاح','id'=>$this->getSerial()];
        }
    }
    public function updateItemHalk(Request $request){
        $getRow = halkDetails::find($request->rowId);
        $oldQty = $getRow->qty;
        $getRow->price = $request->priceUnit;
        $getRow->qty = $request->quantity;
        $getRow->total = $request->finalTotal;
        $getRow->save();
        $getMain = halkMain::find($request->permission);
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
    public function deleteItemHalk(Request $request){
        $getRow = halkDetails::find($request->rowId);
        $oldQty = $getRow->qty;
        $getRow->delete();
        $getMain = halkMain::find($request->permission);
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
    public function deleteHalk(Request $request){
        $details = halkDetails::where(['order_id'=>$request->permission])->get();
        $getMain = halkMain::find($request->permission);
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
            $delDetails = halkDetails::find($detail->id)->delete();
        }
        $getMain->delete();
        if($getMain){
            return ['status'=>true,'data'=>'تم الحذف بنجاح'];
        }
    }

    // Halk Items
    protected function getSerialItem(){
        $serial = 0;
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'stock_halk_mains'");
        $serial = $statement[0]->Auto_increment;
        return $serial;
    }

    public function halkItem(){
        $data['serial'] = $this->getSerialItem();
        $data['halkItems'] = HalkItem::with('getbranch','getsection')->orderBy('id','DESC')->paginate(10);
        $data['branches'] = Branch::get()->all();
        return view('stock.stock.halk_item',$data);
    }
    public function save_halk_item(Request $request){
        $materials = MainComponents::with('Materials')->where(['branch'=>$request->branch,'item'=>$request->item])->first();
        foreach ($materials->materials as $material){
            $materialSection = sectionCost::limit(1)->where(['branch_id'=>$request->branch,'section_id'=>$request->section,'code'=>$material->material_id])->first();
            $unitQty = stock_unit::limit(1)->where(['name'=>$materialSection->unit])->select(['size'])->first();
            $qtyUnit = ($request->qty * $material->quantity) / $unitQty->size;
            $materialSection->qty -= $qtyUnit;
            $materialSection->save();
        }
        $item = Item::find($request->item);
        $saveHalk = HalkItem::create([
            'branch'=>$request->branch,
            'section_id'=>$request->section,
            'item_id'=>$request->item,
            'item'=>$item->name,
            'qty'=>$request->qty,
            'user_id'=>Auth::user()->id,
            'date'=>$request->date,
            'note'=>$request->note
        ]);
        return response()->json([
            'status'=>true,
            'data'=>'تم تهليك الصنف بنجاح',
            'id'=>$this->getSerialItem(),
            'item'=>$item->name
        ]);
    }

    public function deleteHalkItem(Request $request){
        $itemHalk = HalkItem::find($request->id);
        $materials = MainComponents::with('Materials')->where(['branch'=>$itemHalk->branch,'item'=>$itemHalk->item_id])->first();
        foreach ($materials->materials as $material){
            $materialSection = sectionCost::limit(1)->where(['branch_id'=>$itemHalk->branch,'section_id'=>$itemHalk->section_id,'code'=>$material->material_id])->first();
            $unitQty = stock_unit::limit(1)->where(['name'=>$materialSection->unit])->select(['size'])->first();
            $qtyUnit = ($itemHalk->qty * $material->quantity) / $unitQty->size;
            $materialSection->qty += $qtyUnit;
            $materialSection->save();
        }
        $itemHalk->delete();
        return response()->json([
            'status'=>true,
            'data'=>'تم حذف الهالك بنجاح',
        ]);
    }

    public function getHalkOld(Request $request){
        $data = HalkItem::with('getbranch','getsection')->where(['branch'=>$request->branch])->orderBy('id','DESC')->get();
        return ['status'=>true , 'halks'=>$data];
    }
}

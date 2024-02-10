<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\mainMaterialRecipe;
use App\Models\material;
use App\Models\operationsDetails;
use App\Models\operationsMain;
use App\Models\sectionCost;
use App\Models\storeCost;
use App\Models\Units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialOperations extends Controller
{
    protected function getSerial(){
        $serial = 0;
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'stock_operations_mains'");
        $serial = $statement[0]->Auto_increment;
        return $serial;
    }
    public function index(){
        $data['branchs'] = Branch::get()->all();
        $data['serial'] = $this->getSerial();
        return view('stock.stock.material_operations',$data);
    }
    public function getMaterialsComponents(Request $request){
        $materials = sectionCost::where(['section_id'=>$request->section])->get();
        return ['materials'=>$materials];
    }
    public function getMaterialsOperations(Request $request){
        $data = mainMaterialRecipe::with('materials')->limit(1)->where(['material'=>$request->material])->first();
        if(isset($data->materials)){
            for($x=0 ; $x < $data->materials->count() ; $x++){
                $materialCost = sectionCost::limit(1)->where(['branch_id'=>$request->branch,'section_id'=>$request->sections,'code'=>$data->materials[$x]->material_id])->first();
                $unit = Units::limit(1)->where(['name'=>$materialCost->unit])->first();
                $data->materials[$x]->section_qty = $materialCost->qty * $unit->size;
                $data->materials[$x]->unit_size =  $unit->size;
                $data->materials[$x]->one_qty = $data->materials[$x]->quantity / $data->quantity;
            }
            return ['status'=>true,'materials'=>$data];
        }else{
            return ['status'=>false,'msg'=>'material dont have materials recipe'];
        }
    }
    public function saveOperations(Request $request){
        $type = 'section';
        $secStore = $request->sections;
        $data = json_decode($request->materialArray);
        $saveOperationMain = operationsMain::create([
            'branch_id'=>$request->branch,
            'type'=>$type,
            'sec_store'=>$secStore,
            'code'=>$request->components,
            'date'=>$request->date,
            'price'=>$request->priceComp,
            'qty'=>$request->quantityComp,
            'user_id'=>Auth::user()->id,
        ]);
        if($saveOperationMain){
            foreach ($data as $material){
                $saveOperationDetails = operationsDetails::create([
                    'order_id'=>$saveOperationMain->id,
                    'code'=>$material->code,
                    'material'=>$material->itemName,
                    'qty'=>$material->quantity,
                    'price'=>$material->priceUnit,
                    'total'=>$material->total
                ]);
                if($saveOperationDetails){
                    if($type == 'store'){
                        $updateFromCost = storeCost::limit(1)->where(['store_id'=>$secStore,'code'=>$material->code])->first();;
                        $updateFromCost->qty -= $material->quantity / $material->unitSize;
                        $updateFromCost->save();
                    }elseif ($type == 'section'){
                        $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$secStore,'code'=>$material->code])->first();;
                        $updateFromCost->qty -= $material->quantity / $material->unitSize;
                        $updateFromCost->save();
                    }
                }
            }
            if($type == 'store'){
                $updateFromCost = storeCost::limit(1)->where(['store_id'=>$secStore,'code'=>$request->components])->first();;
                if($updateFromCost->f_price == 0.00){
                    $updateFromCost->f_price = $request->priceComp / $request->quantityComp;
                }
                $updateFromCost->average = (($request->priceComp) + ($updateFromCost->l_price * $updateFromCost->qty)) / ($request->quantityComp + $updateFromCost->qty);
                $updateFromCost->l_price = $request->priceComp / $request->quantityComp;
                $updateFromCost->qty += $request->quantityComp;
                $updateFromCost->save();
            }elseif ($type == 'section'){
                $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$secStore,'code'=>$request->components])->first();;
                if($updateFromCost->f_price == 0.00){
                    $updateFromCost->f_price = $request->priceComp / $request->quantityComp;
                }
                $updateFromCost->average = (($request->priceComp) + ($updateFromCost->l_price * $updateFromCost->qty)) / ($request->quantityComp + $updateFromCost->qty);
                $updateFromCost->l_price = $request->priceComp / $request->quantityComp;
                $updateFromCost->qty += $request->quantityComp;
                $updateFromCost->save();
            }
        }
        return ['status'=>true,'data'=>'تم عملية التشغيل بنجاح','id'=>$this->getSerial()];
    }
    public function getOperationViaOrder(Request $request){
        $operation = operationsMain::with('details')->find($request->order);
        return ['status'=>true,'materials'=>$operation];
    }

    public function getSectionCost(Request $request){
        $material = sectionCost::limit(1)->with('sub_unit')->where(['section_id'=>$request->sections,'code'=>$request->material])->first();
        return ['status'=>true,'material'=>$material];
    }
}

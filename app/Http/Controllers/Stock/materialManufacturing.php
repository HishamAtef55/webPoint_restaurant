<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\manufacturingDetails;
use App\Models\manufacturingMain;
use App\Models\sectionCost;
use App\Models\storeCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class materialManufacturing extends Controller
{
    protected function getSerial(){
        $serial = 0;
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'manufacturing_mains'");
        $serial = $statement[0]->Auto_increment;
        return $serial;
    }
    public function index(){
        $data['branchs'] = Branch::get()->all();
        $data['serial'] = $this->getSerial();
        return view('stock.stock.material_manufacturing',$data);
    }
    public function getOrders(Request $request){
        $operation = manufacturingMain::with('details')->find($request->order);
        return ['status'=>true,'materials'=>$operation];
    }
    public function store(Request $request){
        $type = 'section';
        $secStore = $request->sections;
        $data = json_decode($request->materialArray);
        $saveOperationMain = manufacturingMain::create([
            'branch_id'=>$request->branch,
            'type'=>$type,
            'sec_store'=>$secStore,
            'code'=>$request->components,
            'date'=>$request->date,
            'price'=>$request->priceComp,
            'qty'=>$request->quantityComp,
            'halk'=>$request->halk,
            'user_id'=>Auth::user()->id,
        ]);
        if($saveOperationMain){
            foreach ($data as $material){
                $saveOperationDetails = manufacturingDetails::create([
                    'order_id'=>$saveOperationMain->id,
                    'code'=>$material->code,
                    'material'=>$material->itemName,
                    'qty'=>$material->quantity,
                    'price'=>$material->priceUnit,
                    'total'=>$material->total,
                    'type'=>$material->type,
                ]);
                if($saveOperationDetails){
                    if($type == 'store'){
                        $updateFromCost = storeCost::limit(1)->where(['store_id'=>$secStore,'code'=>$material->code])->first();;
                        if($updateFromCost->f_price == 0.00){
                            $updateFromCost->f_price = $material->priceUnit;
                        }
                        $updateFromCost->average = (($material->quantity * $material->priceUnit) + ($updateFromCost->l_price * $updateFromCost->qty)) / ($material->quantity + $updateFromCost->qty);
                        $updateFromCost->l_price = $material->priceUnit;
                        $updateFromCost->qty += $material->quantity;
                        $updateFromCost->save();
                    }elseif ($type == 'section'){
                        $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$secStore,'code'=>$material->code])->first();
                        if($updateFromCost->f_price == 0.00){
                            $updateFromCost->f_price = $material->priceUnit;
                        }
                        $updateFromCost->average = (($material->quantity * $material->priceUnit) + ($updateFromCost->l_price * $updateFromCost->qty)) / ($material->quantity + $updateFromCost->qty);
                        $updateFromCost->l_price = $material->priceUnit;
                        $updateFromCost->qty += $material->quantity;
                        $updateFromCost->save();
                    }
                }
            }
            if($type == 'store'){
                $updateFromCost = storeCost::limit(1)->where(['store_id'=>$secStore,'code'=>$request->components])->first();;
                $updateFromCost->qty -= $request->quantityComp;
                $updateFromCost->save();
            }elseif ($type == 'section'){
                $updateFromCost = sectionCost::limit(1)->where(['section_id'=>$secStore,'code'=>$request->components])->first();;
                $updateFromCost->qty -= $request->quantityComp;
                $updateFromCost->save();
            }
        }
        return ['status'=>true,'data'=>'تم عملية التشغيل بنجاح','id'=>$this->getSerial()];
    }
    public function getMaterials(Request $request){
        $units = [];
        $counter = 0;
        $materials = [];
        if($request->type == 'store'){
            $materials = storeCost::with('sub_unit','sub_unit.sub_unit')->where(['store_id'=>$request->store])->get();
        }elseif ($request->type == 'section'){
            $materials = sectionCost::with('sub_unit','sub_unit.sub_unit')->where(['section_id'=>$request->section])->get();
        }
        return ['status'=>true,'materials'=>$materials];
    }
}

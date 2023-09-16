<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\OpenBalanceSectionDaily;
use App\Models\OpenBalanceStoreDaily;
use App\Models\sectionCost;
use App\Models\storeCost;
use App\Models\Stores;
use Illuminate\Http\Request;

class OpenBalanceDailyController extends Controller
{
    public function index(){
        $branches = Branch::get()->all();
        $stores = Stores::get()->all();
        return view('stock.reports.openBalanceDaily',compact('branches','stores'));
    }
    public function getMaterials(Request $request){
        if($request->type == 'store'){
            $materials = storeCost::where(['store_id'=>$request->store])->where('qty','!=',0)->get();
        }elseif ($request->type == 'section'){
            $materials = sectionCost::where(['section_id'=>$request->section])->where('qty','!=',0)->get();
        }
        return ['status'=>true,'materials'=>$materials];
    }
    public function store(Request $request){
        $materials = json_decode($request->materialArray);
        foreach ($materials as $material){
            $store_branch = 0;
            $section = 0;
            if($request->type == 'store'){
                $chekcLastItem = OpenBalanceStoreDaily::where(['store'=>$request->stores,'material_id'=>$material->code])->latest()->first();
                $openValue = 0;
                if($chekcLastItem){
                    $openValue = $chekcLastItem->close_value;
                }
                $store_branch = $request->stores;
                $create = OpenBalanceStoreDaily::create([
                    'material_id'=>$material->code,
                    'date'=>$request->date,
                    'store'=>$store_branch,
                    'unit_price'=>$material->unitName,
                    'open_value'=>$openValue,
                    'close_value'=>$material->actualBalance,
                    'qty'=>$material->balance,
                ]);
            }elseif ($request->type == 'section'){
                $section = $request->sections;
                $store_branch = $request->branch;
                $chekcLastItem = OpenBalanceSectionDaily::where(['branch'=>$store_branch,'section'=>$section,'material_id'=>$material->code])->latest()->first();
                $openValue = 0;
                if($chekcLastItem){
                    $openValue = $chekcLastItem->close_value;
                }
                $create = OpenBalanceSectionDaily::create([
                    'material_id'=>$material->code,
                    'date'=>$request->date,
                    'branch'=>$store_branch,
                    'section'=>$section,
                    'unit_price'=>$material->unitName,
                    'open_value'=>$openValue,
                    'close_value'=>$material->actualBalance,
                    'qty'=>$material->balance,
                ]);
            }
        }
        return ['status'=>true,'data'=>'تم التسوية بنجاح'];
    }
}

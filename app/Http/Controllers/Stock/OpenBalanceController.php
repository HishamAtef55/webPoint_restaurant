<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Inventory;
use App\Models\OpenBalanceSection;
use App\Models\OpenBalanceSectionDaily;
use App\Models\OpenBalanceStore;
use App\Models\OpenBalanceStoreDaily;
use App\Models\sectionCost;
use App\Models\storeCost;
use App\Models\Stores;
use Illuminate\Http\Request;

class OpenBalanceController extends Controller
{
    public function index(){
        $branches = Branch::get()->all();
        $stores = Stores::get()->all();
        return view('stock.reports.openBalance',compact('branches','stores'));
    }
    public function getMaterials(Request $request){
        if($request->type == 'store'){
            $materials = storeCost::whereHas('MainMaterial',function($query){
                $query->where(['gard'=>1]);
            })->where(['store_id'=>$request->store])->where('qty','!=',0)->get();
        }elseif ($request->type == 'section'){
            $materials = sectionCost::whereHas('MainMaterial',function($query){
                $query->where(['gard'=>1]);
            })->where(['section_id'=>$request->section])->where('qty','!=',0)->get();
        }
        return ['status'=>true,'materials'=>$materials];
    }
    public function store(Request $request){
        $materials = json_decode($request->materialArray);
        foreach ($materials as $material){
            $store_branch = 0;
            $section = 0;
            if($request->type == 'store'){
                $chekcLastItem = OpenBalanceStore::where(['store'=>$request->stores,'material_id'=>$material->code])->latest()->first();
                $openValue = 0;
                if($chekcLastItem){
                    $openValue = $chekcLastItem->close_value;
                }
                $store_branch = $request->stores;
                $create = OpenBalanceStore::create([
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
                $chekcLastItem = OpenBalanceSection::where(['branch'=>$store_branch,'section'=>$section,'material_id'=>$material->code])->latest()->first();
                $openValue = 0;
                if($chekcLastItem){
                    $openValue = $chekcLastItem->close_value;
                }
                $create = OpenBalanceSection::create([
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

<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\detailsComponent;
use App\Models\DetailsItem;
use App\Models\Groups;
use App\Models\InDirectCostDaily;
use App\Models\Item;
use App\Models\material;
use App\Models\Sub_group;
use Illuminate\Http\Request;

class ItemsPricingController extends Controller
{
    public function index(){
        $branchs = Branch::get()->all();
        $materials = material::get()->all();
        $date = Date("Y-m-d");
        $month = date('m', strtotime($date));
        $inDirectCosts = InDirectCostDaily::where('month',$month)->get();
        $inDirectCostsSum = InDirectCostDaily::where('month',$month)->sum('value');
        return view('stock.reports.itemsPricing',compact('branchs','inDirectCosts','inDirectCostsSum','materials'));
    }
    public function getGroups(Request $request){
        $groups = Groups::whereBranchId($request->branch)->get();
        return ['status'=>true,"groups"=>$groups];
    }
    public function getSubGroups(Request $request){
        $subGroup = Sub_group::whereGroupId($request->group)->get();
        return ['status'=>true,"subGroups"=>$subGroup];
    }
    public function getItems(Request $request){
        $material = $request->material;
        if($request->branch != null || $request->branch != ""){
            if($request->groupId == "all"){
                $data = Item::whereHas('material_components',function($query) use($material){
                        if($material != "all") {
                            $query->where(['material_id' => $material]);
                        }
                    })
                    ->whereHas('material_components')->withSum('material_direct','cost')->withSum('material_indirect','cost')->where(['branch_id'=>$request->branch])->get();
            }else{
                if($request->subGroupsId == "all"){
                    $data = Item::whereHas('material_components',function($query) use($material){
                            if($material != "all") {
                                $query->where(['material_id' => $material]);
                            }
                        })
                        ->withSum('material_direct','cost')->withSum('material_indirect','cost')
                        ->where(['branch_id'=>$request->branch])
                        ->where(['group_id'=>$request->groupId])
                        ->get();
                }else{
                    $data = Item::whereHas('material_components',function($query) use($material){
                            if($material != "all") {
                                $query->where(['material_id' => $material]);
                            }
                        })
                        ->withSum('material_direct','cost')->withSum('material_indirect','cost')
                        ->where(['branch_id'=>$request->branch])
                        ->where(['group_id'=>$request->groupId])
                        ->where(['sub_group_id'=>$request->subGroupsId])
                        ->get();
                }
            }
        }else{
            $data = Item::whereHas('material_components',function($query) use($material){
                if($material != "all") {
                    $query->where(['material_id' => $material]);
                }
            })->withSum('material_direct','cost')->withSum('material_indirect','cost')->get();
        }

//        if($request->material != "all"){
//            $data = $data->whereHas('material_components')->get();
//        }

        for($i=0;$i<$data->count();$i++){
            if($data[$i]->material_indirect_sum_cost == null){
                $data[$i]->material_indirect_sum_cost = 0;
            }
            if($data[$i]->cost_price == null){
                $data[$i]->cost_price = 0;
            }
            $details = DetailsItem::with('details','materials.materials')->where('item_id',$data[$i]->id)->orderBy('price','desc')->get();
            $detailsPrice = 0;
            $detailsCost = 0;
            if($details->count() > 0){
                $max = $details[0]->max;
                for ($x=0;$x<$max;$x++){
                    $detailsPrice += $details[$x]->price;
                    $checkCostDetails = detailsComponent::where(['item'=>$data[$i]->id,'details'=>$details[$x]->detail_id])->get();
                    foreach ($checkCostDetails as $row){
                        $detailsCost += $row->cost;
                    }
                }
            }
            $data[$i]['price_details'] = $detailsPrice;
            $data[$i]['cost_details'] = $detailsCost;
        }
        return response()->json(['status'=>true,'items'=>$data]);
    }
}

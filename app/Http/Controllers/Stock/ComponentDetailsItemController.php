<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ComponentsItems;
use App\Models\detailsComponent;
use App\Models\DetailsItem;
use App\Models\Items;
use App\Models\MainComponents;
use App\Models\mainDetailsComponent;
use App\Models\MainGroup;
use App\Models\material;
use Illuminate\Http\Request;

class ComponentDetailsItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public $status = false;
    public function index(){
        $branchs = Branch::get()->all();
        $groups = MainGroup::get()->all();
        return view('stock.stock.componentDetailsItems',compact('branchs','groups'));
    }
    public function getItemDetails(Request $request){
        $items = Items::whereHas('details')->where(['branch_id'=>$request->branch])->select(['id','name','price'])->get();
        if($items){
            return response()->json([
                'status'=>true,
                'items'=>$items
            ]);
        }
    }
    public function getDetails(Request $request){
        if(!$request->branch){return['status'=>false,'data'=>'select branch please'];}
        if(!$request->item){return['status'=>false,'data'=>'select item please'];}
        if($request->branch && $request->item){
            $details = DetailsItem::with('details')->where(['item_id'=>$request->item])->get();
            $data = [];
            $counter = 0;
            foreach ($details as $detail){
                $data[$counter]['id'] = $detail->details->id;
                $data[$counter]['name'] = $detail->details->name;
                $data[$counter]['price'] = $detail->price;
                $counter++;
            }
            return ['status'=>true,'data'=>$data];
        }
    }
    public function saveDetailsComponent(Request $request){
        if($request->materialArray != null){
            $mainId = 0;
            if(mainDetailsComponent::limit(1)->where(['branch' =>$request->branch,'item'=>$request->items,'details'=>$request->details])->count() == 0){
                $saveMain = mainDetailsComponent::create([
                    'branch' =>$request->branch,
                    'item'=>$request->items,
                    'cost'=>$request->totalPrice,
                    'percentage'=>$request->percentage,
                    'details'=>$request->details,
                    'quantity'=>$request->productQty,
                ]);
                $mainId = $saveMain->id;
            }else{
                mainDetailsComponent::limit(1)->where(['branch' =>$request->branch,'item'=>$request->items,'details'=>$request->details])->update([
                    'cost'=>$request->totalPrice,
                    'percentage'=>$request->percentage,
                    'quantity'=>$request->productQty
                ]);
                $saveMain = mainDetailsComponent::limit(1)->where(['branch' =>$request->branch,'item'=>$request->items,'details'=>$request->details])->first();
                $mainId = $saveMain->id;
            }
            foreach ($request->materialArray as $material){
                if(detailsComponent::where(['branch'=>$request->branch,'item'=>$request->items,'details'=>$request->details,'material_id'=>$material['code']])->count() > 0){
                    $save = detailsComponent::where(['branch'=>$request->branch,'item'=>$request->items,'details'=>$request->details,'material_id'=>$material['code']])
                        ->update([
                            'cost'     =>$material['price'],
                            'quantity' =>$material['quantity'],
                        ]);
                }else{
                    $save = detailsComponent::create([
                        'branch'        =>$request->branch,
                        'main_id'       =>$mainId,
                        'item'       =>$request->items,
                        'details'       =>$request->details,
                        'material_id'   =>$material['code'],
                        'material_name' =>$material['name'],
                        'cost'          =>$material['price'],
                        'quantity'      =>$material['quantity'],
                    ]);
                }
            }
            if($save){
                return response()->json([
                    'status'=>true,
                    'data'=>'تم اضافة المكونات بنجاح'
                ]);
            }
        }
    }
    public function deleteDetailsRecipe(Request $request){
        $del_item  = detailsComponent::limit(1)->where(['branch'=>$request->branch,'item'=>$request->items,'details'=>$request->details,'material_id'=>$request->code])->delete();
        if($del_item){
            mainDetailsComponent::limit(1)->where(['branch'=>$request->branch,'item'=>$request->items,'details'=>$request->details])->update([
                'cost'=>$request->totalPrice,
                'percentage'=>$request->percentage
            ]);
        }
        return response()->json([
            'status'=>true,
            'data'=>'تم حذف المكون بنجاح'
        ]);
    }
    public function getMaterialsInDetails(Request $request){
        $materials = mainDetailsComponent::with('materials')->where(['branch'=>$request->branch,'item'=>$request->item,'details'=>$request->details])->first();
        return response()->json([
            'status'=>true,
            'materials'=>$materials
        ]);
    }
    public function transfierMaterialDetails(Request $request){
        $chekMain = false;
        $sum = 0;
        $main = array();
        $addItem = array();
        $mainId = 0;
        $branch = $request->materials[0]['branch'];
        $item = $request->materials[0]['item_id'];
        $details = $request->materials[0]['details'];
        if(mainDetailsComponent::limit(1)->where(['branch'=>$branch,'item'=>$item,'details'=>$details])->count() == 0){
            $addItem['branch']=$branch;
            $addItem['item']=$item;
            $addItem['details']=$details;
            $addMain = mainDetailsComponent::create($addItem);
            $mainId = $addMain->id;
        }else{
            $data = mainDetailsComponent::limit(1)->where(['branch'=>$branch,'item'=>$item,'details'=>$details])->first();
            $mainId = $data->id;
        }
        foreach ($request->materials as $material){
            if(detailsComponent::limit(1)->where(['main_id'=>$mainId,'material_id'=>$material['material_id']])->count() == 0){
                $main['branch'] = $material['branch'];
                $main['item'] = $material['item_id'];
                $main['details'] = $material['details'];
                $main['material_id'] = $material['material_id'];
                $main['material_name'] = $material['material_name'];
                $main['quantity'] = $material['quantity'];
                $main['cost'] = $material['cost'];
                $main['main_id'] = $mainId;
                $sum += $material['cost'];
                $addMaterial = detailsComponent::create($main);
            }else{
                detailsComponent::limit(1)->where(['main_id'=>$mainId,'material_id'=>$material['material_id']])->update([
                    'cost' => $material['cost'],
                    'quantity' => $material['quantity'],
                ]);
                $sum += $material['cost'];
            }
        }
        $costDetails = DetailsItem::limit(1)->where(['branch_id'=>$branch,'item_id'=>$item,'detail_id'=>$details])->select(['price'])->first();
        $data = mainDetailsComponent::limit(1)->where(['branch'=>$branch,'item'=>$item,'details'=>$details])->update([
            'cost'=>$sum,
            'percentage'=>number_format($sum / $costDetails->price * 100, 2, '.', '')
        ]);
        return ['status'=>true,'data'=>'تم تكرار المكونات بنجاح'];
    }
    public function DetailsWithoutMaterials(Request $request){
        $items = [];
        $details = [];
        $counter_item = 0;
        $counter_details = 0;
        if($request->branch){
            $item = Items::with('details','details.details')->whereHas('details')
                ->where(['branch_id'=>$request->branch])->select(['id','name','price'])->get();
            foreach ($item as $oneItem){
                $items[$counter_item]['id'] = $oneItem->id;
                $items[$counter_item]['name'] = $oneItem->name;
                $items[$counter_item]['price'] = $oneItem->price;
                foreach ($oneItem->details as $oneDetails){
                    if(detailsComponent::limit(1)->where(['branch'=>$request->branch,'item'=>$oneItem->id,'details'=>$oneDetails->detail_id])->count() == 0){
                        $details[$counter_details]['id'] = $oneDetails->detail_id;
                        $details[$counter_details]['name'] = $oneDetails->details->name;
                        $details[$counter_details]['price'] = $oneDetails->price;
                        $counter_details++;
                    }
                }
                $counter_details = 0;
                $items[$counter_item]['details'] = $details;
                $counter_item++;
                $details = [];
            }
            $this->status = true;
        }else{
            $items = 'select branch please';
        }
        return response()->json([
            'status'=>$this->status,
            'data'=>$items,
        ]);
    }
    public function printDetails(Request $request){
        $data = '';
        $status = false;
        if(!$request->branch){$data = 'select branch please';}
        if($request->items && $request->details){
            $details = $request->details;
            $data = Items::limit(1)->with(['details_components'=>function($query)use($details){
                $query->where('details',$details);
            },'details_components.materials','details_components.details'])->where(['branch_id'=>$request->branch,'id'=>$request->items])->select(['id','name','cost_price','price'])->first();
            $status = true;
        }elseif (!$request->details && $request->items){
            $data = Items::limit(1)->with('details_components','details_components.materials','details_components.details')
                ->where(['branch_id'=>$request->branch,'id'=>$request->items])->select(['id','name','cost_price','price'])->first();
            $status = true;
        }else{
            $data = 'select item please';
        }
        return ['status'=>$status,'data'=>$data];
    }
}

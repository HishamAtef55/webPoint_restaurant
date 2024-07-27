<?php

namespace App\Http\Controllers\Stock;

use App\Models\MainGroup;
use Illuminate\Http\Request;
use App\Models\MainComponents;
use App\Models\materialRecipe;
use App\Models\Stock\Material;
use App\Models\ComponentsItems;
use App\Models\Stock\StockGroup;
use App\Models\mainMaterialRecipe;
use App\Http\Controllers\Controller;

class MaterialRecipeController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    public $status = false;
    public function index(){
        $groups = StockGroup::get();
        $materials = Material::get();
        return view('stock.stock.material_recipe',compact('materials','groups'));
    }
    public function saveMaterialRecipe(Request $request){
        if($request->materialArray != null) {
            $mainId = 0;
            if(mainMaterialRecipe::limit(1)->where(['material'=>$request->material])->count() == 0){
                $save = mainMaterialRecipe::create([
                    'material'=>$request->material,
                    'quantity'=>$request->productQty,
                    'cost'=>$request->totalPrice,
                    'percentage'=>$request->percentage,
                ]);
                $mainId = $save->id;
            }else{
                $main = mainMaterialRecipe::limit(1)->where(['material'=>$request->material])->first();
                $mainId = $main->id;
                $main->quantity = $request->productQty;
                $main->cost = $request->totalPrice;
                $main->percentage = $request->percentage;
                $main->save();
            }
            foreach ($request->materialArray as $material){
                if(materialRecipe::limit(1)->where(['main_id'=>$mainId,'material_id'=>$material['code']])->count() == 0){
                    materialRecipe::create([
                        'main_id' =>$mainId,
                        'material_id' =>$material['code'],
                        'quantity' =>$material['quantity'],
                        'cost' =>$material['price'],
                        'material_name' =>$material['name'],
                    ]);
                }else{
                    $materialUpdate = materialRecipe::limit(1)->where(['main_id'=>$mainId,'material_id'=>$material['code']])->first();
                    $materialUpdate->quantity = $material['quantity'];
                    $materialUpdate->cost = $material['price'];
                    $materialUpdate->save();
                }
            }
            return ['status'=>true,'data'=>'تم حفظ مكونات الخامة'];
        }
    }
    public function getRecipeMaterialInMaterials(Request $request){
        $data = mainMaterialRecipe::with('materials')->limit(1)->where(['material'=>$request->material])->first();
        return ['status'=>true,'materials'=>$data];
    }
    public function transferMaterialRecipe(Request $request){
        $chekMain = false;
        $sum = 0;
        $main = array();
        $addItem = array();
        $mainId = 0;
        $material = $request->materials[0]['item_id'];
        if(mainMaterialRecipe::limit(1)->where(['material'=>$material])->count() == 0){
            $addItem['material']=$material;
            $addMain = mainMaterialRecipe::create($addItem);
            $mainId = $addMain->id;
        }else{
            $data = mainMaterialRecipe::limit(1)->where(['material'=>$material])->first();
            $mainId = $data->id;
        }
        foreach ($request->materials as $material){
            if(materialRecipe::limit(1)->where(['main_id'=>$mainId,'material_id'=>$material['material_id']])->count() == 0){
                $main['material_id'] = $material['material_id'];
                $main['material_name'] = $material['material_name'];
                $main['quantity'] = $material['quantity'];
                $main['cost'] = $material['cost'];
                $main['main_id'] = $mainId;
                $sum += $material['cost'];
                $addMaterial = materialRecipe::create($main);
            }else{
                materialRecipe::limit(1)->where(['main_id'=>$mainId,'material_id'=>$material['material_id']])->update([
                    'cost' => $material['cost'],
                    'quantity' => $material['quantity'],
                ]);
                $sum += $material['cost'];
            }
        }
        $costDetails = material::limit(1)->where(['code'=>$material])->select(['price'])->first();
        if($costDetails->price == 0){$costDetails->price = 1;}
        $data = mainMaterialRecipe::limit(1)->where(['id'=>$mainId])->update([
            'cost'=>$sum,
            'percentage'=>number_format($sum / $costDetails->price * 100, 2, '.', '')
        ]);
        return ['status'=>true,'data'=>'تم تكرار المكونات بنجاح'];
    }
    public function deleteMaterialRecipe(Request $request){
        $del_item  = materialRecipe::limit(1)->where(['main_id'=>$request->code,'material_id'=>$request->material])->delete();
        if($del_item){
            mainMaterialRecipe::limit(1)->where(['id'=>$request->code])->update([
                'cost'=>$request->totalPrice,
                'percentage'=>$request->percentage
            ]);
            return response()->json([
                'status'=>true,
                'data'=>'تم حذف المكون بنجاح'
            ]);
        }

    }
    public function getMaterialReports(Request $request){
        if(!$request->material){$data = 'برجاء اختيار الخامة';}
        if($request->material){
            $this->status = true;
            $data = material::limit(1)->with(['materialRecipe'=>function($query){
                $query->select(['quantity','cost','percentage','material','id']);
            },'materialRecipe.materials'])->where(['code'=>$request->material])->select(['name','code'])->first();
        }
        return ['status'=>$this->status,'data'=>$data];
    }
    public function getMaterialsReports(Request $request){
        $this->status = true;
        $data = material::whereHas('materialRecipe')->with(['materialRecipe'=>function($query){
            $query->select(['quantity','cost','percentage','material','id']);
        },'materialRecipe.materials'])->select(['name','code'])->get();
        return ['status'=>$this->status,'data'=>$data];
    }
}

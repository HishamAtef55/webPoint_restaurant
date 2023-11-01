<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\MainGroup;
use App\Models\material;
use App\Models\material_group;
use App\Models\MaterialSections;
use App\Models\sectionCost;
use App\Models\stocksection;
use App\Models\storeCost;
use App\Models\Stores;
use App\Models\Units;
use http\Env\Response;
use App\Http\Requests\MaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function view_material(){
        $mainGroup = MainGroup::get()->all();
        $units = Units::select(['name'])->get();
        $branchs = Branch::get()->all();
        $materials = material::with('group')->orderBy('id','DESC')->get();
        return view('stock.stock.material',compact('mainGroup','units','branchs','materials'));
    }
    protected function getID($subGroupId){
        $subGroup = material_group::limit(1)->where(['id'=>$subGroupId])->select(['start_serial','end_serial'])->first();
        $newCode = 0;
        if(material::where(['sub_group'=>$subGroupId])->count() > 0){
            $newCode = material::where(['sub_group'=>$subGroupId])->max('code') + 1;
        }else{
            $newCode =  $subGroup->start_serial;
        }
        return $newCode;
    }
    public function get_sub_group(Request $request){
        $subGroup = material_group::where(['main_group'=>$request->mainGroup])->get();
        return response()->json(['status'=>'true','subGroup'=>$subGroup]);
    }
    public function get_group_code(Request $request){
        return response()->json(['status'=>'true','code'=>$this->getID($request->subGroup)]);
    }
    public function get_sections_branch(Request $request){
        $sections = stocksection::where(['branch'=>$request->branch])->select(['name','id'])->get();
        if($sections){
            return response()->json(['status'=>true,'sections'=>$sections]);
        }
    }
    public function save_material(MaterialRequest $request){
        $insertMaterial = material::create([
            'main_group'    =>$request->mainGroup,
            'sub_group'     =>$request->subGroup,
            'code'          =>$request->materialId,
            'name'          =>$request->materialName,
            'cost'          =>$request->standardCost,
            'price'         =>$request->price,
            'unit'          =>$request->unit,
            'manfu'         =>$request->manfu,
            'packing'       =>$request->packing,
            'loss'          =>$request->lossRatio,
            'min_store'     =>$request->storeLimitMin,
            'max_store'     =>$request->storeLimitMax,
            'min_section'   =>$request->sectionLimitMin,
            'max_section'   =>$request->sectionLimitMax,
            'storage'       =>$request->storeMethod,
            'expire'        =>$request->expire,
            'gard'          =>$request->dailyInventory,
            'all_group'     =>$request->allGroup,
        ]);
        if($insertMaterial){
            foreach ($request->section as $section){
                if(isset($section['sections'])){
                    foreach ($section['sections'] as $add){
                        $insert = MaterialSections::create([
                            'branch'    =>$section['id'],
                            'material'  =>$request->materialId,
                            'section'   =>$add['id'],
                        ]);
                        sectionCost::create([
                            'branch_id'=>$section['id'],
                            'section_id'=>$add['id'],
                            'code'=>$request->materialId,
                            'material'=>$request->materialName,
                            'unit' =>$request->unit,
                        ]);
                    }
                }
            }
            foreach (Stores::get()->all() as $store){
                storeCost::create([
                    'store_id'=>$store->id,
                    'code'=>$request->materialId,
                    'material'=>$request->materialName,
                    'unit' =>$request->unit,
                ]);
            }
            if($insert){
                $materials = material::with('group')->orderBy('id','DESC')->get();
                return response()->json(['status'=>'true','msg'=>'تم اضافة الخامة بنجاح','code'=>$this->getID($request->subGroup),'materials'=>$materials]);
            }
        }

    }
    public function search_material_using_name(Request $request){
        $query = $request['query'];
        $materials = material::where('name', 'LIKE', '%' . $query . "%")->select(['id','name'])->get();
        if($materials){return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$materials]);}
    }
    public function get_material_in_ul(Request $request){
        $materials = material::with('Sections')->where(['id'=>$request->id])->first();
        return response()->json([
            'data'   =>$materials,
            'status' =>'true'
        ]);
    }
    public function update_material(UpdateMaterialRequest $request){
        $updateMaterial = material::limit(1)->where(['code'=>$request->materialId])->update([
            'main_group'    =>$request->mainGroup,
            'sub_group'     =>$request->subGroup,
            'name'          =>$request->materialName,
            'cost'          =>$request->standardCost,
            'price'         =>$request->price,
            'unit'          =>$request->unit,
            'manfu'         =>$request->manfu,
            'packing'       =>$request->packing,
            'loss'          =>$request->lossRatio,
            'min_store'     =>$request->storeLimitMin,
            'max_store'     =>$request->storeLimitMax,
            'min_section'   =>$request->sectionLimitMin,
            'max_section'   =>$request->sectionLimitMax,
            'storage'       =>$request->storeMethod,
            'expire'        =>$request->expire,
            'gard'          =>$request->dailyInventory,
            'all_group'     =>$request->allGroup,
        ]);
        if($updateMaterial){
            $del_material_sections = MaterialSections::where(['material'=>$request->materialId])->delete();
            foreach ($request->section as $section){
                if(isset($section['sections'])){
                    foreach ($section['sections'] as $add){
                        $insert = MaterialSections::create([
                            'branch'    =>$section['id'],
                            'material'  =>$request->materialId,
                            'section'   =>$add['id'],
                        ]);
                        if(sectionCost::limit(1)->where(['section_id'=>$add['id'],'code'=>$request->materialId])->count() == 0){
                            sectionCost::create([
                                'branch_id'=>$section['id'],
                                'section_id'=>$add['id'],
                                'code'=>$request->materialId,
                                'material'=>$request->materialName,
                                'unit'          =>$request->unit,
                            ]);
                        }
                    }
                }
            }
            if($insert){
                $materials = material::with('group')->orderBy('id','DESC')->get();
                return response()->json(['status'=>'true','msg'=>'تم تعديل الخامة بنجاح','materials'=>$materials]);
            }
        }
    }
}

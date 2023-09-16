<?php

namespace App\Http\Controllers\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SectionRequest;
use App\Models\Branch;
use App\Models\Groups;
use App\Models\Stores;
use App\Models\stocksection;
use App\Models\section_group;
use App\Models\section_store;

class SectionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    private function getNextUserId()
    {
        $statement  = DB::select("SHOW TABLE STATUS LIKE 'stocksections'");
        $nextUserId = $statement[0]->Auto_increment;
        return $nextUserId;
    }
    public function view_section(){
        $new_section = $this->getNextUserId();
        $branchs = Branch::get()->all();
        $stores  = Stores::get()->all();
        $sections = stocksection::with('sectionsBranch','sectionstore')->get()->all();
        return view('stock.stock.sections',compact(['new_section','branchs','stores','sections']));
    }
    public function get_group(Request $request){
        $groups = Groups::where(['branch_id'=>$request->branch])->select(['id','name'])->get();
        return response()->json(['status'=>'true','groups'=>$groups]);
    }
    public function save_section(SectionRequest $request){
        $section = stocksection::create([
            'name'=>$request->name,
            'branch'=>$request->branch,
        ]);
        if($section){
            foreach ($request->groups as $group){
                $savegroup = section_group::create([
                    'section_id' =>$section->id,
                    'group_id'   =>$group['id'],
                    'group_name' =>$group['name'],
                ]);
            }
            $savestoresec = section_store::create([
                'store_id'=>$request->store,
                'section_id'=>$section->id,
            ]);
            $new_section = $this->getNextUserId();
            return response()->json(['status'=>'true','msg'=>'تم حفظ القسم بنجاح','new_section'=>$new_section]);
        }
    }
    public function search_section(Request $request){
        $query = $request['query'];
        $sections = stocksection::where(['branch'=>$request->branch])->where('name', 'LIKE', '%' . $query . "%")->select(['id','name'])->get();
        if($sections){return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$sections]);}
    }
    public function get_section(Request $request){
        $data = stocksection::limit(1)->with(['sectiongroup','sectionstore'])->where('id',$request->id)->first();
        return response()->json(['status'=>'true','msg'=>'All Data For Search','data'=>$data]);

    }
    public function update_section(SectionRequest $request){
        $section = stocksection::where(['id'=>$request->id])->update([
            'name'=>$request->name,
            'branch'=>$request->branch,
        ]);
        if($section){
            $del = section_group::where(['section_id'=>$request->id,])->delete();
            foreach ($request->groups as $group){
                    $savegroup = section_group::create([
                        'section_id' =>$request->id,
                        'group_id'   =>$group['id'],
                        'group_name' =>$group['name'],
                    ]);
                }
            $del = section_store::where(['section_id'=>$request->id,])->delete();
            $savestoresec = section_store::create([
                'store_id'=>$request->store,
                'section_id'=>$request->id,
            ]);
            $new_section = $this->getNextUserId();
            return response()->json(['status'=>'true','msg'=>'تم تعديل القسم بنجاح','new_section'=>$new_section]);
        }
    }
}

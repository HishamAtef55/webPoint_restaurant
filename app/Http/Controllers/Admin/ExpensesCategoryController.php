<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpensesCategory;
use App\Traits\All_Functions;
use App\Models\Branch;
class ExpensesCategoryController extends Controller
{
    use All_Functions;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data    = ExpensesCategory::get();
        $branchs = Branch::get()->all();
        return view('control.Expenses',compact('data','branchs'));
    }

    public function search(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = ExpensesCategory::with('branch')->where('title', 'LIKE', '%' . $query . "%")->get();
            if($data)
            {
                return response()->json($data);
            }
        }
    }

    public function save(Request $request){
        if(ExpensesCategory::where(['branch_id'=>$request->branch_id ,'title'=>$request->name])->count() > 0){
            return response()->json(['status'=>false,'msg'=>'This Expenses Already Exist']);
        }
        ExpensesCategory::create([
            'title'     => $request->name,
            'branch_id' => $request->branch_id,
        ]);
        return response()->json(['status'=>true,'msg'=>'Add Successfualy']);
    }

    function action(Request $request)
    {
        if($request->ajax())
        {
            if($request->action == 'edit')
            {
                if(ExpensesCategory::where(['branch_id'=>$request->branch_id ,'title'=>$request->name])->count() > 0){
                    return response()->json(['status'=>false,'msg'=>'This Expenses Already Exist']);
                }
                $data = array(
                    'title'  => $request->$name,
                );
                ExpensesCategory::where('id', $request->id)
                    ->update($data);
                return response()->json($request);
            }
            if($request->action == 'delete')
            {
                ExpensesCategory::where('id',$request->id)->delete();
                return Response()->json($request);
            }

        }
    }
}

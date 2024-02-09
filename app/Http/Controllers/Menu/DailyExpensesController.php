<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyExpenses;
use App\Models\ExpensesCategory;
use App\Traits\All_Functions;
use App\Traits\All_Notifications_menu;
use App\Http\Requests\DailyExpensesRequest;

class DailyExpensesController extends Controller
{
    use All_Functions;
    use All_Notifications_menu;
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $this->CheckLastOrder();
        $this->CheckWaitFail();
        $this->removeActionTable();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();

        $expenses = DailyExpenses::where(['branch_id'=>$this->GetBranch(),'date'=>$this->CheckDayOpen()])->orderBy('id','DESC')->get();
        $category = ExpensesCategory::where(['branch_id'=>$this->GetBranch()])->get();
        
        return view('menu.Expenses',compact
        ([
            'to_noti_hold',
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'expenses',
            'category'
        ]));
    }

    public function save(DailyExpensesRequest $request){
        DailyExpenses::create([
            'amount'    => $request->amount,
            'expense_id'=> $request->category,
            'note'      => $request->note,
            'user_id'   => $this->GetUser(),
            'branch_id' => $this->GetBranch(),
            'date'      => $this->CheckDayOpen(),
            'time'      => $this->Get_Time(),
        ]);
        return  response()->json(['status'=>'true']);
    }

    public function delete(Request $request){
        DailyExpenses::where(['id'=>$request->id])->delete();
        return response()->json(['status'=>true],200);
    }
}

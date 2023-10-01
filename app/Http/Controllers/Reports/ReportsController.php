<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Group;
use App\Models\Orders_d;
use App\Models\Shift;
use App\Models\Void_d;
use App\Models\System;
use App\Models\Wait_order_m;
use App\Models\Void_m;
use App\Models\Item;
use App\Traits\All_Functions;
use App\Traits\All_Notifications_menu;
use App\Models\User;
use App\Models\Orders_m;
use App\Models\LogTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Matrix\Builder;


class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Notifications_menu;
    use All_Functions;
    public function view_sales_current(){
        $this->calcBeforeCloseShift();
        $date = $this->CheckDayOpen();
        $branch = $this->GetBranch();
        $this->removeActionTable();
        $this->CheckLastOrder();
        $orders = Orders_d::where(['state'=>'0','d_order'=>$date])->get();
        $shifts = Shift::where(['branch_id'=>$branch])->get();
        $users = User::where(['branch_id'=>$branch])->get();
        $devices = Device::where(['branch_id'=>$branch])->get();

        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.sales_current_report',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
            'shifts',
            'users',
            'devices'
        ]));
    }
    public function view_daily_report(){
        $date = $this->CheckDayOpen();
        $branch = $this->GetBranch();
        $this->removeActionTable();
        $this->CheckLastOrder();
        $orders = Orders_d::where(['state'=>'0','d_order'=>$date])->get();
        $shifts = Shift::where(['branch_id'=>$branch])->get();
        $users = User::where(['branch_id'=>$branch])->get();
        $devices = Device::where(['branch_id'=>$branch])->get();

        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.daily_reposrt',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
            'shifts',
            'users',
            'devices'
        ]));
    }
    public function view_water_sales_report(){
        $date = $this->CheckDayOpen();
        $branch = $this->GetBranch();
        $this->removeActionTable();
        $this->CheckLastOrder();
        $orders = Orders_d::where(['state'=>'0','d_order'=>$date])->get();
        $shifts = Shift::where(['branch_id'=>$branch])->get();
        $users = User::where(['branch_id'=>$branch])->get();
        $devices = Device::where(['branch_id'=>$branch])->get();

        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.waiter_sales',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
            'shifts',
            'users',
            'devices'
        ]));
    }
    public function view_shift_sales_report(){
        $date = $this->CheckDayOpen();
        $branch = $this->GetBranch();
        $this->removeActionTable();
        $this->CheckLastOrder();
        $orders = Orders_d::where(['state'=>'0','d_order'=>$date])->get();
        $shifts = Shift::where(['branch_id'=>$branch])->get();
        $devices = Device::where(['branch_id'=>$branch])->get();

        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.shift_sales',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
            'shifts',
            'devices'
        ]));
    }
    public function view_transfer_report(){
        $branch = $this->GetBranch();
        $this->removeActionTable();
        $this->CheckLastOrder();
        $devices = Device::where(['branch_id'=>$branch])->get();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.transfer_sales',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
            'devices'
        ]));
    }
    public function view_discount_report(){
        $this->removeActionTable();
        $branch = $this->GetBranch();
        $this->CheckLastOrder();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.discount_report',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
        ]));
    }
    public function view_void_report(){
        $this->removeActionTable();
        $branch = $this->GetBranch();
        $this->CheckLastOrder();
        $users = User::where(['branch_id'=>$branch])->get();

        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.void_sales',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
            'users'
        ]));
    }
    public function view_item_report(){
        $this->removeActionTable();
        $branch = $this->GetBranch();
        $this->CheckLastOrder();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.sales_item',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
        ]));
    }
    public function view_cost_report(){
        $this->removeActionTable();
        $this->CheckLastOrder();
        $branch = $this->GetBranch();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.costreport',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
        ]));
    }
    public function view_cost_sold_report(){
        $this->removeActionTable();
        $this->CheckLastOrder();
        $branch = $this->GetBranch();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('Reports.cost_sold_report',compact([
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'to_noti_hold',
        ]));
    }
    ################################ search_water_sales_report ##############################
    public function search_water_sales_report(Request $request){
        $branch       = $this->GetBranch();
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;
        $orders       = [];
        $date = $this->Get_Date();
        // Check Of Report in one Day Or Period
        if($date == $request->from && $date == $request->to){
            $orders = Orders_d::select([
                'op','method','user_id','user','gust','total','order_id'])->get();
        }
        else if($request->from == $request->to || $request->to == null){ // is Filter by Using One Day
            $orders = Orders_m::where(['branch_id'=>$branch ,'d_order'=>$request->from])->select([
                'op','method','user_id','user','gust','total','order_id'])->get();
        }else{
            $orders = Orders_m::where(['branch_id'=>$branch])
                ->whereBetween('d_order',[$request->from,$request->to])
                ->select(['op','method','user_id','user','gust','total','order_id'])->get();
        }

        // Filter By Transaction
        if(isset($request->trans)){
            $orders = $orders->whereIn('op',$request->trans);
        }

        // Filter By Bay-Way
        if(isset($request->bay_way)){
            $orders = $orders->whereIn('method',$request->bay_way);
        }
        // Filter By Users
        if(isset($request->user)){
            $orders = $orders->whereIn('user_id',$request->user);
        }
        // Group py User
        $data = $orders->groupBy('user_id')->map(function ($row,$key) {
            return [
                'waiter'   =>$row[0]['user'],
                'orders'   =>$row->count('order_id'),
                'total'    =>$row->sum('total'),
                'guest'    =>$row->sum('gust'),
                'avg'      =>$row->sum('total') / $row->sum('gust'),
            ];
        });
        return response()->json([
            'status'=>'true',
            'res_nmae' =>$res_name,
            'waiters'=>$data
        ]);
    }
    ################################ search_water_sales_report ##############################
    public function search_shift_sales_report(Request $request){
        $branch       = $this->GetBranch();
        $orders       = [];
        $currentDate = $this->Get_Date();
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;
        // Check Of Report in one Day Or Period
        if($request->from == $request->to || $request->to == null){ // is Filter by Using One Day
            $orders = Orders_d::with(['ShiftOpen'])->where(['state'=>0,'branch_id'=>$branch ,'d_order'=>$request->from])->select([
                'op','method','shift','gust','total','order_id'])->get();
            if($orders->count() == 0){
                $orders = Orders_m::with(['ShiftOpen'])->where(['branch_id'=>$branch ,'d_order'=>$request->from])->select([
                    'op','method','shift','gust','total','order_id'])->get();
            }
        }else{
            $orders = Orders_m::with(['ShiftOpen'])->where(['branch_id'=>$branch])
                ->whereBetween('d_order',[$request->from,$request->to])
                ->select(['op','method','shift','gust','total','order_id'])->get();
        }

        // Filter By Transaction
        if(isset($request->trans)){
            $orders = $orders->whereIn('op',$request->trans);
        }

        // Filter By Bay-Way
        if(isset($request->bay_way)){
            $orders = $orders->whereIn('method',$request->bay_way);
        }
        // Filter By Users
        if(isset($request->shift)){
            $orders = $orders->whereIn('shift',$request->shift);
        }
        // Group py User
        $data = $orders->groupBy('shift')->map(function ($row,$key) {
            return [
                'shift'    =>$row[0]['shift'],
                'orders'   =>$row->count('order_id'),
                'total'    =>$row->sum('total'),
                'guest'    =>$row->sum('gust'),
                'avg'      =>$row->sum('total') / $row->sum('gust'),
            ];
        });
        return response()->json([
            'status'=>'true',
            'res_nmae' =>$res_name,
            'shifts'=>$data
        ]);
    }
    ############################### search_transfer_report #################################
    public function search_transfer_report(Request $request){
        $branch = $this->GetBranch();
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;
        // Check Of Report in one Day Or Period
        if($request->from == $request->to || $request->to == null){ // is Filter by Using One Day
            $trans = LogTransfer::where(['branch'=>$branch ,'date'=>$request->from , 'type'=>$request->type])->get();
        }else{
            $trans = LogTransfer::where(['branch'=>$branch,'type'=>$request->type])
                ->whereBetween('date',[$request->from,$request->to])->get();
        }
        return response()->json(['status'=>'true','res_nmae' =>$res_name,'trans'=>$trans]);
    }
    ################################ search_discount_report ##########################
    public function search_discount_report(Request $request){
        $branch       = $this->GetBranch();
        $orders       = [];
        $date    = $this->Get_Date();
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;
        if($date == $request->from && $date == $request->to){
            $orders = Orders_d::where('total_discount','!=',0)
                ->select(['d_order','op','method','user','total_discount','total','discount_type','order_id','cashier'])->get();

        }
        // Check Of Report in one Day Or Period
        else if($request->from == $request->to || $request->to == null){ // is Filter by Using One Day
            $orders = Orders_m::where(['branch_id'=>$branch ,'d_order'=>$request->from])
                ->where('discount','!=',0)
                ->select(['d_order','op','method','user','total_discount','total','discount_type','order_id','cashier'])->get();

        }else{
            $orders = Orders_m::where(['branch_id'=>$branch])
                ->whereBetween('d_order',[$request->from,$request->to])
                ->where('discount','>',0)
                ->select(['d_order','op','method','user','total_discount','total','discount_type','order_id','cashier'])->get();
        }
        // Filter By Transaction
        if(isset($request->trans)){
            $orders = $orders->whereIn('op',$request->trans);
        }
        // Filter By Bay-Way
        if(isset($request->bay_way)){
            $orders = $orders->whereIn('method',$request->bay_way);
        }
        return response()->json(['status'=>'true','res_nmae' =>$res_name,'orders'=>$orders]);
    }
    ############################### Void TReports ###################################
    public function search_void_report(Request $request){
        $branch  = $this->GetBranch();
        $date    = $this->Get_Date();
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;
        // Check Of Report in one Day Or Period
        if($date == $request->to &&  $date == $request->from){
            $voids = Void_d::where(['branch_id'=>$branch ,'date'=>$request->from])
                ->select(['order_id','date','user','user_id','name','quantity','table_id','total','status'])->get();
        }
        else if($request->from == $request->to || $request->to == null){ // is Filter by Using One Day
            $voids = Void_m::where(['branch_id'=>$branch ,'date'=>$request->from])
                ->select(['order_id','date','user','user_id','name','quantity','table_id','total','status'])->get();
        }else{
            $voids = Void_m::where(['branch_id'=>$branch])
                ->whereBetween('date',[$request->from,$request->to])
                ->select(['order_id','date','user','user_id','name','quantity','table_id','total','status'])->get();
        }
        // Filter By Users Void_d
        if(isset($request->user)){
            $voids = $voids->whereIn('user_id',$request->user);
        }
        // Filter By Type
        if(isset($request->type)){
            if($request->type != 'all'){
                $voids = $voids->where('status',$request->type);
            }
        }
        return response()->json(['res_nmae' =>$res_name,'status'=>'true','voids'=>$voids]);
    }
    ############################### search_item_report #####################################
    public function search_item_report(Request $request){
        $branch = $this->GetBranch();
        $data = [];
        $count = 0;
        $flag = 0;
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;
        $wait = Wait_order_m::where(['branch_id'=>$branch])
        ->whereBetween('d_order',[$request->from,$request->to])
        ->select(['item_id','name'])->get();

        $items = Item::where(['branch_id'=>$branch])->select(['name','id'])->get();
        foreach($items as $item){
            foreach($wait as $w){
                if($item->id == $w->item_id){
                    $flag = 1;
                }
            }
            if($flag == 0){
                $data[$item->id] = ['id'=>$item->id,'name'=>$item->name];
            }
            $flag = 0;
        }
        return response()->json([
            'status'=>'true',
            'res_nmae' =>$res_name,
            'items'=>$data
        ]);
    }
    ############################## Cost Reports ########################################
    public function costReport(Request $request){
        $branch  = $this->GetBranch();
        $date    = $this->Get_Date();
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;
        $data =[];
        $counter = 0;
        $helpcount = 0;
        $currentDate = 0;
        $orders = Orders_m::with('WaitOrders','WaitOrders.item','WaitOrders.Extra','WaitOrders.Extra.mainextra','WaitOrders.Details')->where(['branch_id'=>$branch])
            ->whereBetween('d_order',[$request->from,$request->to])
            ->select(['d_order','total','order_id','id'])->get();
        foreach ($orders as $order){
            if($currentDate != $order->d_order){
                if($helpcount != 0){$counter++;}
                $helpcount++;
                $currentDate = $order->d_order;
                $data[$counter]['date'] = $currentDate;
                $data[$counter]['total'] = $order->total;
                foreach ($order->WaitOrders as $wait){
                    if(isset($data[$counter]['cost'])){
                        $data[$counter]['cost'] += $wait->item->cost_price;
                    }else{
                        $data[$counter]['cost'] = $wait->item->cost_price;
                    }
                    if(!empty($wait->extra)){
                        foreach ($wait->extra as $extra){
                            $data[$counter]['cost'] += $extra->mainextra->cost_price;
                        }
                    }
                }
            }else{
                $data[$counter]['total'] += $order->total;
                foreach ($order->WaitOrders as $wait){
                    if(isset($data[$counter]['cost'])){
                        $data[$counter]['cost'] += $wait->item->cost_price;
                    }
                    if(!empty($wait->extra)){
                        foreach ($wait->extra as $extra){
                            $data[$counter]['cost'] += $extra->mainextra->cost_price;
                        }
                    }
                }
            }
        }
//        return $orders;
        return response()->json([
            'status'=>'true',
            'res_nmae' =>$res_name,
            'orders'=>$data
        ]);
    }
    ######################################### view_cost_sold_report #########################
    public function cost_sold_report(Request $request){
        $to = $request->to;
        $from = $request->from;
        $groups = Group::with('Supgroups','Supgroups.items','Supgroups.items.WaitOrderM')->whereHas('Supgroups.items.WaitOrderM',function ($query) use ($to,$from){
            $query->where('wait_orders_m.d_order','2022-11-26')->get();
        })->get();
        return $groups;
    }

}

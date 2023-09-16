<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Days;
use App\Models\Details_Wait_Order;
use App\Models\Details_Wait_Order_m;
use App\Models\Extra_wait_order;
use App\Models\Extra_wait_order_m;
use App\Models\Orders_d;
use App\Models\Orders_m;
use App\Models\Wait_order;
use App\Models\Wait_order_m;
use App\Traits\All_Functions;
use Illuminate\Http\Request;

class OpenDayController extends Controller
{
  use All_Functions;
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('permission:admin');
  }
    public function index(){
        $branchs = Branch::get()->all();
        return view('control.open_day',compact('branchs'));
    }

    public function getDaysUsingBranch(Request $request){
        $days = Days::where(['branch'=>$request->branch,'active'=>0])->orderBy('id','desc')->select(['id','date'])->get();
        return response()->json(['status'=>true,'data'=>$days]);
    }

    public function openDay(Request $request){
        if(Orders_d::count() > 0){
            return ['status'=>false];
        }
        $orders = Orders_m::with('WaitOrders','WaitOrders.Details','WaitOrders.Extra')->where(['d_order'=>$request->date])->get();
        if($orders->count() > 0){
            foreach ($orders as $order) {
                Orders_d::create([
                    'id'    => $order->id,
                    'order_id' => $order->order_id,
                    'dev_id' => $order->dev_id,
                    'table' => $order->table,
                    'serial_shift'=>$order->serial_shift,
                    'op' => $order->op,
                    'state' => $order->state,
                    'sub_total' => $order->sub_total,
                    'delivery' => $order->delivery,
                    'user' => $order->user,
                    'user_id' => $order->user_id,
                    'branch_id' => $order->branch_id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer_name,
                    't_order' => $order->t_order,
                    'd_order' => $order->d_order,
                    'take_order' => $order->take_order,
                    'delivery_order' => $order->delivery_order,
                    'to_pilot' => $order->to_pilot,
                    'pilot_account' => $order->pilot_account,
                    'hold_list' => $order->hold_list,
                    'time_hold_list' => $order->time_hold_list,
                    'date_holde_list' => $order->date_holde_list,
                    'pilot_id' => $order->pilot_id,
                    'pilot_name' => $order->pilot_name,
                    'location' => $order->location,
                    'discount' => $order->discount ?? 0,
                    'discount_name' => $order->discount_name ?? "",
                    'discount_type' => $order->discount_type ?? "",
                    'total_discount' => $order->total_discount ?? 0,
                    'total_details' => $order->total_details,
                    'total_extra' => $order->total_extra,
                    'total' => $order->total,
                    'shift' => $order->shift,
                    'cashier' => $order->cashier,
                    'services' => $order->services,
                    'service_ratio' => $order->service_ratio,
                    'state_service' => $order->state_service,
                    'tax' => $order->tax,
                    'tax_ratio' => $order->tax_ratio,
                    'state_tax' => $order->state_tax,
                    'discount_tax_service' => $order->discount_tax_service,
                    'min_charge' => $order->min_charge,
                    'gust' => $order->gust,
                    'method' => $order->method,
                    'no_print' => $order->no_print,
                    'tip' => $order->tip,
                    'cash' => $order->cash,
                    'visa' => $order->visa,
                    'hos' => $order->hos,
                    'r_bank' => $order->r_bank,
                    'devcashier' => $order->devcashier,
                    't_closeorder' => $order->t_closeorder,
                ]);
                foreach ($order->WaitOrders as $wa) {
                    $ins = Wait_order::create([
                        'id'    => $wa->id,
                        'order_id' => $wa->order_id,
                        'state' => $wa->state,
                        'item_id' => $wa->item_id,
                        'op' => $wa->op,
                        'table_id' => $wa->table_id,
                        'sub_num_order' => $wa->sub_num_order,
                        'moved' => $wa->moved,
                        'name' => $wa->name,
                        'quantity' => $wa->quantity,
                        'price' => $wa->price,
                        'total' => $wa->total,
                        'total_extra' => $wa->total_extra,
                        'price_details' => $wa->price_details,
                        'discount_name' => $wa->discount_name,
                        'discount_type' => $wa->discount_type,
                        'discount' => $wa->discount,
                        'total_discount' => $wa->total_discount,
                        'comment' => $wa->comment,
                        'without' => $wa->without,
                        'pick_up' => $wa->pick_up,
                        'user' => $wa->user,
                        'user_id' => $wa->user_id,
                        'branch_id' => $wa->branch_id,
                        'subgroup_id' => $wa->subgroup_id,
                        'subgroup_name' => $wa->subgroup_name,
                    ]);
                    foreach ($wa->Details as $det){
                        Details_Wait_Order::create([
                            'id'=>$det->id,
                            'number_of_order'=>$det->number_of_order,
                            'detail_id'=>$det->detail_id,
                            'price'=>$det->price,
                            'name'=>$det->name,
                            'wait_order_id'=>$ins->id,
                        ]);
                        $del = Details_Wait_Order_m::where('id',$det->id)->delete();
                    }
                    foreach ($wa->Extra as $ex){
                        $new_order = Extra_wait_order::create([
                            'id'=>$ex->id,
                            'extra_id'=>$ex->extra_id,
                            'wait_order_id'=>$ins->id,
                            'number_of_order'=>$ex->number_of_order,
                            'price'=>$ex->price,
                            'name'=>$ex->name,
                        ]);
                        $del = Extra_wait_order_m::where('id',$ex->id)->delete();
                    }
                    $del = Wait_order_m::where('order_id',$wa->order_id)->delete();
                }
                $del = Orders_m::where('order_id',$order->order_id)->delete();
            }
            $days = Days::where(['branch'=>$request->Branch,'active'=>1])->first();
            if($days){
                $days->active = 0;
                $days->time_close  = $this->Get_Time();
                $days->save();
            }
            $daysOpen = Days::where(['branch'=>$request->Branch,'date'=>$request->date])->first();
            $daysOpen->active = 1;
            $daysOpen->time_close  = null;
            $daysOpen->save();
            return ['status'=>true];
        }else{
            return ['status'=>true];
        }
    }

    public function emptyTable(Request $request){
        Orders_d::truncate();
        Wait_order::truncate();
        Details_Wait_Order::truncate();
        Extra_wait_order::truncate();
        return ['status'=>true];
    }
}

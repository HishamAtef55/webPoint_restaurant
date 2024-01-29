<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\CloseShift;
use App\Models\CloseShiftDaily;
use App\Models\CloseShiftGroup;
use App\Models\Device;
use App\Models\Group;
use App\Models\Orders_d;
use App\Models\Orders_m;
use App\Models\Service_tables;
use App\Models\Shift;
use App\Models\Wait_order;
use App\Models\Wait_order_m;
use App\Traits\All_Functions;
use App\Traits\All_Notifications_menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CopyCloseShift extends Controller
{
    use All_Functions;
    use All_Notifications_menu;
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $shifts = Shift::get();
        $this->CheckLastOrder();
        $this->CheckWaitFail();
        $this->removeActionTable();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold     = $this->TOGO_hold();

        return view('menu.copy_close_shift',compact
        ([
            'to_noti_hold',
            'shifts',
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
        ]));
    }
    public function getData($reqDate , $reqShift){
        $date     = $reqDate;
        $branch   = $this->GetBranch();
        $time     = $this->Get_Time();
        $shift    = Shift::where(['branch_id'=>$branch,'id'=>$reqShift])->first();
        $shift_id = $shift->shiftid;
        $shift_na = $shift->shift;
        $discount_item = 0;
        $data = array(
            'branch'=>$branch, 'date'=>$date, 'time'=>$time, 'shift_id'=>$shift_id, 'shift'=>$shift_na,
            'order_no'=>0, 'min_order'=>0, 'max_order'=>0, 'gust_no'=>0, 'gust_avarge'=>0, 'sub_total'=>0,
            'cash'=>0, 'visa'=>0, 'hos'=>0, 'total_cash'=>0, 'table'=>0, 'delivery'=>0, 'to_go'=>0, 'table_ser'=>0,
            'delivery_ser'=>0, 'to_go_ser'=>0, 'table_tax'=>0, 'delivery_tax'=>0, 'to_go_tax'=>0, 'table_no'=>0,
            'delivery_no'=>0, 'to_go_no'=>0, 'tax'=>0, 'service'=>0, 'discount'=>0, 'details'=>0, 'extras'=>0,
            'tip'=>0, 'r_bank'=>0, 'customer_payments'=>0,
        );
        $groups = Group::where(['branch_id'=>$branch])->select(['id','name'])->get();
        for($i = 0 ; $i < $groups->count() ; $i++){
            $groups[$i]['total'] = 0;
            $groups[$i]['quantity'] = 0;
            $groups[$i]['total_pre'] = 0;
        }
        if(Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->count() > 0){
            // Cal Order_No
            $data['order_no'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->count();
            // Cal Order_Min
            $data['min_order'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->min('order_id');
            // Cal Order_Max
            $data['max_order'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->max('order_id');
            // Cal gust_no
            $data['gust_no'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('gust');
            // Cal sub_total
            $data['sub_total'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('sub_total');
            // Cal cash
            $data['cash'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('cash');
            // Cal visa
            $data['visa'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('visa');
            // Cal hos
            $sud_hos = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'hos'=>1])->sum('sub_total');
            $sud_tax = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'hos'=>1])->sum('tax');
            $sud_ser = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'hos'=>1])->sum('services');
            $sud_dis = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'hos'=>1])->sum('total_discount');
            $data['hos'] = $sud_hos + $sud_tax + $sud_ser - $sud_dis;
            // Cal total_cash
            $data['total_cash'] = $data['cash'] + $data['visa'] + $data['hos'];
            $data['gust_avarge'] = $data['cash'] / $data['gust_no'];

            // Cal total_cash
            $data['customer_payments'] = $data['cash'] + $data['visa'];

            // Calculate Toatal table
            $data['table'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'Table'])->sum('total');
            // Calculate Toatal Delivery
            $data['delivery'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'Delivery'])->sum('total');
            // Calculate Toatal TO_GO
            $data['to_go'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'TO_GO'])->sum('total');

            // Calculate Toatal table_service
            $data['table_ser'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'Table'])->sum('services');
            // Calculate Toatal Delivery_service
            $data['delivery_ser'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'Delivery'])->sum('services');
            // Calculate Toatal TO_GO_service
            $data['to_go_ser'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'TO_GO'])->sum('services');

            // Calculate Toatal table_TAX
            $data['table_tax'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'Table'])->sum('tax');
            // Calculate Toatal Delivery_TAX
            $data['delivery_tax'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'Delivery'])->sum('tax');
            // Calculate Toatal TO_GO_TAX
            $data['to_go_tax'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'TO_GO'])->sum('tax');


            // Calculate Toatal table_NO_ORDER
            $data['table_no'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'Table'])->count();
            // Calculate Toatal Delivery_NO_ORDER
            $data['delivery_no'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'Delivery'])->count();
            // Calculate Toatal TO_GO_NO_ORDER
            $data['to_go_no'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch,'op'=>'TO_GO'])->count();


            // Calculate Toatal tax
            $data['tax'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('tax');
            // Calculate Toatal service
            $data['service'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('services');

            // Calculate Toatal tip
            $data['tip'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('tip');
            // Calculate Toatal r_bank
            $data['r_bank'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('r_bank');

            // Calculate Toatal Extra
            $data['extras'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('total_extra');
            // Calculate Toatal Details
            $data['details'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('total_details');
            $all_orders = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->select(['order_id'])->get();
            foreach($all_orders as $order)
            {
                $wait = Wait_order_m::where(['order_id'=>$order->order_id])->select(['subgroup_id','all_total','total_discount','price_details','total_extra','total','quantity','total_discount'])->get();
                foreach($wait as $w){
                    $discount_item += $w->total_discount;
                    for($x = 0 ; $x < $groups->count() ; $x++){
                        if($groups[$x]['id'] == $w->subgroup_id){
                            $groups[$x]['quantity'] += $w->quantity;
                            $groups[$x]['total'] += $w->total + $w->total_extra + $w->price_details - $w->total_discount;
                        }
                    }
                }
            }

            // Calculate Toatal discount
            $data['discount'] = Orders_m::where(['d_order'=>$date,'shift'=>$shift_id,'branch_id'=>$branch])->sum('total_discount') + $discount_item;

            // Calculate The Pr
            $all_total_groups = $groups->sum('total');
            for($x = 0 ; $x < $groups->count() ; $x++){
                $groups[$x]['total_pre'] += $groups[$x]['total'] / $data['sub_total'] * 100;
            }
        }
        return ['data'=>$data,'groups'=>$groups,'status'=>true];
    }
    public function view_close_check(Request $request){
        if(!$request->date || !$request->shift){
            return ['status'=>false];
        }else{
            $data = $this->getData($request->date,$request->shift);
            return $data;
        }
    }
    public function print_close_shift(Request $request){
        if(!$request->date || !$request->shift){
            return ['status'=>false];
        }else{
            $data = $this->getData($request->date,$request->shift);
            $branch   = $this->GetBranch();
            $insert_data = CloseShift::create($data['data']);
            CloseShiftGroup::truncate();
            foreach($data['groups'] as $gr){
                CloseShiftGroup::create([
                    'close_shift'=>$insert_data->id,
                    'name'=>$gr->name,
                    'total'=>$gr->total,
                    'quantity'=>$gr->quantity,
                    'total_pre'=> $gr->total_pre,
                ]);
            }
            $device_print = Device::limit(1)->where(['branch_id'=>$branch,'id_device'=>$request->devId])->first();
            $order_print = array('branch'=>$branch,'order_id'=>'0','type'=>9,'no_copies'=>1,'val_type'=>'0','printer'=>$device_print->printer_invoice);
            $this->OrderPrint($order_print);
            return $data;
        }
    }
}

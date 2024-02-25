<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Device;
use App\Models\discounts;
use App\Models\extra;
use App\Models\Group;
use App\Models\Item;
use App\Models\Locations;
use App\Models\Orders_d;
use App\Models\menu;
use App\Models\Others;
use App\Models\Printers;
use App\Models\SerialCheck;
use App\Models\SerialShift;
use App\Models\Sub_group;
use App\Models\ToGo;
use App\Models\Wait_order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\All_Notifications_menu;
use App\Traits\All_Functions;
class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Notifications_menu;
    use All_Functions;
    public function Delivery_Order()
    {
        $this->CheckLastOrder();
        $this->fixDeleveryStatus();
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $day_now = $this->CheckDayOpen();
        $pilots = User::where('branch_id',Auth::user()->branch_id)
        ->where('job_id',3)
        ->get();
        $orders = Orders_d::with(['WaitOrders','Locations'])
            ->where('branch_id',Auth::user()->branch_id)
            ->where('op','Delivery')
            ->where('state','0')
            ->where('delivery_order','1')
            ->where('d_order',$day_now)
            ->select(['order_id','customer_name','user','location','pilot_name','pilot_id','discount','discount_type','total'])
            ->get();
        $locations = [];
        $pilots_o    = [];
        $countrer = 0;
        $flag = 0;
        $countr_p = 0;
        $flag_p = 0;
        $op_cal  = 'Delivery';
        $alltaxandservice = [];
        $counter_tax = 0;
        foreach ($orders as $order)
        {
            $taxandservice = $this->calculate_taxandservice($op_cal , $order->order_id);
            $alltaxandservice[$order->order_id] = [
                'total'          =>$taxandservice[0]['total'],
                'tax_ratio'      =>$taxandservice[0]['tax_ratio'],
                'tax'            =>$taxandservice[0]['tax'],
                'service_ratio'  =>$taxandservice[0]['service_ratio'],
                'service'        =>$taxandservice[0]['service'],
                'discount'       =>$taxandservice[0]['discount'],
            ];
            $counter_tax++;
            if($countr_p == 0)
            {
                $pilots_o[$countr_p] = [
                    'id'       =>$order->pilot_id,
                    'pilot'    =>$order->pilot_name,
                ];
                $countr_p++;
            }else{
                foreach ($pilots_o as $search)
                {
                    if($search['id'] == $order->pilot_id)
                    {
                        $flag_p = 1 ;
                    }
                }
                if($flag_p == 1)
                {
                    $flag_p = 0;
                }else{
                    $pilots_o[$countr_p] = [
                        'id'       =>$order->pilot_id,
                        'pilot'    =>$order->pilot_name,
                    ];
                    $countr_p++;
                    $flag_p = 0;
                }
            }
        }
        foreach ($orders as $order)
        {
            if($countrer == 0)
            {
                $locations[$countrer] = [
                    'id'       =>$order->locations->id,
                    'location' =>$order->locations->location,
                ];
                $countrer++;
            }else{
                foreach ($locations as $search)
                {
                    if($search['id'] == $order->locations->id)
                    {
                        $flag = 1 ;
                    }
                }
                if($flag == 1)
                {
                    $flag = 0;
                }else{
                    $locations[$countrer] = [
                        'id'       =>$order->locations->id,
                        'location' =>$order->locations->location,
                    ];
                    $countrer++;
                    $flag = 0;
                }
            }
        }

        $to_noti_hold     = $this->TOGO_hold();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();

        return view('menu.delivery_order',
            compact([
                'to_noti_hold',
                'pilots_o',
                'locations',
                'orders',
                'del_noti',
                'del_noti_to_pilot',
                'del_noti_pilot',
                'del_noti_hold',
                'pilots'
            ]));
    }
    public function to_pilot()
    {
        $this->CheckLastOrder();
        $this->fixDeleveryStatus();
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $day_now = $this->CheckDayOpen();
        $pilots = User::where('branch_id',Auth::user()->branch_id)
            ->where('job_id',3)
            ->get();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold     = $this->TOGO_hold();


        $orders = Orders_d::with(['WaitOrders','Locations'])
            ->where('branch_id',Auth::user()->branch_id)
            ->where('op','Delivery')
            ->where('state','1')
            ->where('to_pilot',1)
            ->where('d_order',$day_now)
            ->select(['total','order_id','customer_name','user','location','discount','discount_type'])
            ->get();
        $locations = [];
        $countrer = 0;
        $flag = 0;
        $op_cal  = 'Delivery';
        $alltaxandservice = [];
        $counter_tax = 0;
        foreach ($orders as $order)
        {
            $taxandservice = $this->calculate_taxandservice($op_cal , $order->order_id);
            $alltaxandservice[$order->order_id] = [
                'total'          =>$taxandservice[0]['total'],
                'tax_ratio'      =>$taxandservice[0]['tax_ratio'],
                'tax'            =>$taxandservice[0]['tax'],
                'service_ratio'  =>$taxandservice[0]['service_ratio'],
                'service'        =>$taxandservice[0]['service'],
                'discount'       =>$taxandservice[0]['discount'],
            ];
            $counter_tax++;
            if($countrer == 0)
            {
                $locations[$countrer] = [
                    'id'       =>$order->locations->id,
                    'location' =>$order->locations->location,
                ];
                $countrer++;
            }else{
                foreach ($locations as $search)
                {
                    if($search['id'] == $order->locations->id)
                    {
                        $flag = 1 ;
                    }
                }
                if($flag == 1)
                {
                    $flag = 0;
                }else{
                    $locations[$countrer] = [
                        'id'       =>$order->locations->id,
                        'location' =>$order->locations->location,
                    ];
                    $countrer++;
                    $flag = 0;
                }
            }
        }
        return view('menu.to_pilot',
            compact([
                'to_noti_hold',
                'alltaxandservice',
                'locations',
                'orders',
                'del_noti',
                'del_noti_to_pilot',
                'del_noti_pilot',
                'del_noti_hold',
                'pilots'
            ]));
    }
    public function hold_list()
    {
        $this->CheckLastOrder();
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $day_now = $this->CheckDayOpen();
       $orders = Orders_d::with('WaitOrders')
          ->where('branch_id',Auth::user()->branch_id)
          ->where('op','Delivery')
          ->where('state','1')
          ->where('hold_list',1)
          ->where('d_order',$day_now)
          ->select(['order_id','customer_name','user','location','time_hold_list','date_holde_list','discount','discount_type'])
          ->get();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold     = $this->TOGO_hold();

        $op_cal  = 'Delivery';
        $alltaxandservice = [];
        $counter_tax = 0;
        foreach ($orders as $order)
        {
            $taxandservice = $this->calculate_taxandservice($op_cal , $order->order_id);
            $alltaxandservice[$order->order_id] = [
                'total'          =>$taxandservice[0]['total'],
                'tax_ratio'      =>$taxandservice[0]['tax_ratio'],
                'tax'            =>$taxandservice[0]['tax'],
                'service_ratio'  =>$taxandservice[0]['service_ratio'],
                'service'        =>$taxandservice[0]['service'],
                'discount'       =>$taxandservice[0]['discount'],
            ];
        }

        return view('menu.hold_delivery',
            compact([
                'to_noti_hold',
                'alltaxandservice',
                'orders',
                'del_noti',
                'del_noti_to_pilot',
                'del_noti_pilot',
                'del_noti_hold'
            ]));

    }
    public function pilot_account()
    {
        $this->CheckLastOrder();
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $day_now = $this->CheckDayOpen();
        $pilots = User::where('branch_id',Auth::user()->branch_id)
            ->where('job_id',3)
            ->get();
        $orders = Orders_d::with(['Locations'])
            ->where('branch_id',Auth::user()->branch_id)
            ->where('op','Delivery')
            ->where('state','1')
            ->where('d_order',$day_now)
            ->where('pilot_account',1)
            ->select(['total','order_id','customer_name','user','location','pilot_name','pilot_id','discount','discount_type','delivery'])
            ->get();
        $locations = [];
        $pilots_o    = [];
        $countrer = 0;
        $flag = 0;
        $countr_p = 0;
        $flag_p = 0;
        $op_cal  = 'Delivery';
        $alltaxandservice = [];
        $counter_tax = 0;
        foreach ($orders as $order)
        {
            $taxandservice = $this->calculate_taxandservice($op_cal , $order->order_id);
            $alltaxandservice[$order->order_id] = [
                'total'          =>$taxandservice[0]['total'],
                'tax_ratio'      =>$taxandservice[0]['tax_ratio'],
                'tax'            =>$taxandservice[0]['tax'],
                'service_ratio'  =>$taxandservice[0]['service_ratio'],
                'service'        =>$taxandservice[0]['service'],
                'discount'       =>$taxandservice[0]['discount'],
            ];
            $counter_tax++;
            if($countr_p == 0)
            {
                $pilots_o[$countr_p] = [
                    'id'       =>$order->pilot_id,
                    'pilot'    =>$order->pilot_name,
                ];
                $countr_p++;
            }else{
                foreach ($pilots_o as $search)
                {
                    if($search['id'] == $order->pilot_id)
                    {
                        $flag_p = 1 ;
                    }
                }
                if($flag_p == 1)
                {
                    $flag_p = 0;
                }else{
                    $pilots_o[$countr_p] = [
                        'id'       =>$order->pilot_id,
                        'pilot'    =>$order->pilot_name,
                    ];
                    $countr_p++;
                    $flag_p = 0;
                }
            }
        }
        foreach ($orders as $order)
        {
            if($countrer == 0)
            {
                $locations[$countrer] = [
                    'id'       =>$order->locations->id,
                    'location' =>$order->locations->location,
                ];
                $countrer++;
            }else{
                foreach ($locations as $search)
                {
                    if($search['id'] == $order->locations->id)
                    {
                        $flag = 1 ;
                    }
                }
                if($flag == 1)
                {
                    $flag = 0;
                }else{
                    $locations[$countrer] = [
                        'id'       =>$order->locations->id,
                        'location' =>$order->locations->location,
                    ];
                    $countrer++;
                    $flag = 0;
                }
            }
        }
        $to_noti_hold     = $this->TOGO_hold();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();

        return view('menu.pilot_account',
            compact([
                'to_noti_hold',
                'pilots_o',
                'locations',
                'orders',
                'del_noti',
                'del_noti_to_pilot',
                'del_noti_pilot',
                'del_noti_hold',
                'pilots'
            ]));
    }
    public function edit_order($order_id)
    {
        $this->CheckLastOrder();
        $state_cus = 'Edit_customer';
        $new_order = $order_id;
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        $no_print = 0;

        $operation = "Delivery";
        $branch = Auth::user()->branch_id;
        $menus = menu::where('branch_id',$branch)
            ->get();
        foreach($menus as $menu)
        {
            if($menu->active == 1)
            {
                $id_active_menu = $menu->id;
                break;
            }
        }
        // Select All Sub Group in This Branch
        $subgroup = Group::where('branch_id',$branch)
            ->where('menu_id',$id_active_menu)
            ->get();
        // Select All Wait Order In This table
        $dis_name = '';
        $dis_val  = 0;
        $dis_type  ='Ratio';
        $dis = 0;
        $state_ser = 0;
        $state_tax = 0;
        $gust    = 0;
        $Orders   = Wait_order::with(['Details','Extra'])->where('order_id',$order_id)->get();
        $order_dis = Orders_d::limit(1)->where(['branch_id'=>$branch,'order_id'=>$order_id])
            ->select(['no_print','hold_list','op','gust','min_charge','user','customer_name','discount','discount_name','discount_type','state_service','state_tax','delivery'])->first();
        $dis_name = $order_dis->discount_name;
        $dis_type = $order_dis->discount_type;
        $dis = $order_dis->discount;
        $state_ser = $order_dis->state_service ;
        $state_tax = $order_dis->state_tax;
        $delivery  = $order_dis->delivery;
        $mincharge = $order_dis->min_charge;
        $capten    = $order_dis->user;
        $customer  = $order_dis->customer_name;
        $customer  = $order_dis->no_print;
        $check_hold = 0 ;
        if($order_dis->hold_list == 1){
            $check_hold = 1;
        }

        $wait = Wait_order::where('order_id',$order_id)
            ->select(['total','total_extra','price_details','total_discount'])->get();
        $total_wait = 0;
        foreach ($wait as $ser)
        {
            $total_wait = $total_wait + $ser->total + $ser->total_extra + $ser->price_details - $ser->total_discount;
        }
        if($order_dis->discount_type == 'Ratio')
        {
            $cal_ratio = ($order_dis->discount / 100) * $total_wait;
            $dis_val =$cal_ratio;
            $dis_val = bcadd($dis_val,'0',2);
        }elseif ($order_dis->discount_type == 'Value')
        {
            $dis_val = $order_dis->discount;
            $dis_val = bcadd($dis_val,'0',2);
        }
        // Get Discount
        $discount_ratio = discounts::where('branch_id',$branch)
            -> where('type','Ratio')
            ->get();
        $discount_value = discounts::where('branch_id',$branch)
            -> where('type','Value')
            -> get();
        $customer_order = Orders_d::where('branch_id',Auth::user()->branch_id)
            ->where('order_id',$order_id)
            ->get();
        $locations = Locations::with('Branch')->where('branch_id',$branch)->get();
        if($dis_type == null)
        {
            $dis_type = 'Ratio';
            $dis = '0';
        }
        $operation = $order_dis->op;
        $taxandservice = $this->calculate_taxandservice($operation , $order_id);
        $printers = Printers::where(['active'=>'1'])->get();
        return view('menu.menu',compact
        ([
            'no_print', 'mincharge', 'customer', 'printers', 'capten',
            'delivery', 'state_tax', 'state_ser', 'taxandservice', 'locations', 'dis',
            'dis_type', 'dis_val', 'dis_name', 'customer_order', 'state_cus',
            'del_noti', 'del_noti_to_pilot', 'del_noti_pilot', 'del_noti_hold',
            'to_noti_hold', 'operation', 'menus', 'subgroup', 'Orders',
            'new_order', 'discount_value', 'discount_ratio', 'operation', 'check_hold'
        ]));
    }
    public function Remove_Delivery(Request $request)
    {
        $branch = $this->GetBranch();
        $last_order = SerialCheck::where(['branch_id'=>$branch])->select(['order'])->orderBy('id', 'desc')
            ->first();
        if($last_order->order == $request->order_id){
            $remove_sub = Wait_order::where('branch_id',$branch)->where('order_id',$request->order_id)->delete();
            $del          = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order_id])->delete();
            $del_serial   = SerialCheck::where(['branch_id'=>$branch,'order'=>$request->order_id])->delete();
            $del_sershift = SerialShift::limit(1)->where(['branch'=>$branch,'order_id'=>$request->order_id])->delete();
        }else{
            $order_test = Item::limit(1)->where(['name'=>'TEST'])->first();
            $group = Group::limit(1)->where('branch_id',$branch)
                ->where('id',$order_test->group_id)
                ->get()
                ->first();
            $remove_sub = Wait_order::where('branch_id',$branch)->where('order_id',$request->order_id)->delete();
            $time_now = $this->Get_Time();
            $data_test = Wait_order::create(
                [
                    'item_id'            => $order_test->id,
                    'name'               => $order_test->name,
                    'price'              => $order_test->price,
                    'order_id'           => $request->order_id,
                    'table_id'           => 0,
                    'status_take'        => 1,
                    'pick_up'            => 1,
                    'sub_num_order'      => 1,
                    'subgroup_id'        => $group->id,
                    'subgroup_name'      => $group->name,
                    'total'              => $order_test->price,
                    'quantity'           => 1,
                    'op'                 => $request->operation,
                    'state'              => 0,
                    'user'               => Auth::user()->name,
                    'user_id'            => Auth::user()->id,
                    'branch_id'          => Auth::user()->branch_id,
                ]
            );
            $dev_order = 0;
            $to_pilot = 0;
            $update_or = Orders_d::limit(1)->where('order_id',$request->order_id)->update([
                'state' => 0,
                'method'=>'cash',
                'no_print'=>1,
                'cash'=>0,
                'services'=>0,
                'tip'=>0,
                'visa'=>0,
                'delivery_order' => 1,
                'to_pilot'=>0,
                'delivery' => 0,
                't_closeorder'=>$time_now,
            ]);
            $this->AddTotalOrder($request->operation,$request->order_id);
        }
        return response()->json(['status'=>'true']);
    }
    public function add_pilot_delivery(Request $request)
    {
        $get_pilot = User::where('id',$request->pilot)->get()->first();
        $add_pilot = Orders_d::where('branch_id',Auth::user()->branch_id)
            ->where('order_id',$request->order_id)
            ->update([
                'pilot_id'      => $request->pilot,
                'pilot_name'    => $get_pilot->name,
                'to_pilot'      => 0,
                'pilot_account' => 1,
            ]);
        if($add_pilot)
        {
            return response()->json(['status'=>'true']);
        }
    }
    public function Search_order_delivery(Request $request)
    {
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $day_now = $this->CheckDayOpen();
        $alltaxandservice = [];
        if($request->page == 'pilot_account')
        {
            if ($request->get('query')) {
                $query = $request->get('query');
                if($query == "all")
                {
                    $data =Orders_d::with(['WaitOrders','Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('pilot_account',1)
                        ->where('d_order',$day_now)
                        ->select(['total_discount','total','order_id','customer_name','user','location','pilot_name'])->get();
                }else{
                    $data =Orders_d::with(['WaitOrders','Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('pilot_account',1)
                        ->where('d_order',$day_now)
                        ->where('location', 'LIKE', '%' . $query . "%")
                        ->select(['total_discount','total','order_id','customer_name','user','location','pilot_name'])->get();
                }

            }
        }elseif ($request->page == 'to_pilot')
        {
            if ($request->get('query')) {
                $query = $request->get('query');
                if($query == "all")
                {
                    $data =Orders_d::with(['Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('to_pilot',1)
                        ->where('d_order',$day_now)
                        ->select(['total_discount','total','order_id','customer_name','user','location','pilot_name'])->get();
                }else{
                    $data =Orders_d::with(['Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('to_pilot',1)
                        ->where('d_order',$day_now)
                        ->where('location', 'LIKE', '%' . $query . "%")
                        ->select(['total_discount','total','order_id','customer_name','user','location','pilot_name'])->get();
                }
            }

        }
        elseif ($request->page == 'delivery_order')
        {
            if ($request->get('query')) {
                $query = $request->get('query');
                if($query == "all")
                {
                    $data =Orders_d::with(['WaitOrders','Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('state','0')
                        ->where('d_order',$day_now)
                        ->select(['total_discount','total','order_id','customer_name','user','location','pilot_name'])->get();
                }else{
                    $data =Orders_d::with(['WaitOrders','Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('state','0')
                        ->where('d_order',$day_now)
                        ->where('location', 'LIKE', '%' . $query . "%")
                        ->select(['total_discount','total','order_id','customer_name','user','location','pilot_name'])->get();
                }


            }
        }
        return response()->json($data);
    }
    public function Search_pilot_delivery(Request $request)
    {
        $alltaxandservice = [];
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $day_now = $this->CheckDayOpen();
        if($request->page == 'pilot_account')
        {
            if ($request->get('query')) {
                $query = $request->get('query');
                if($query == "all")
                {
                    $data =Orders_d::with(['Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('pilot_account',1)
                        ->where('d_order',$day_now)
                        ->select(['total','order_id','customer_name','user','location','pilot_name'])->get();
                }else{
                    $data =Orders_d::with(['Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('pilot_account',1)
                        ->where('d_order',$day_now)
                        ->where('pilot_id', 'LIKE', '%' . $query . "%")
                        ->select(['total','order_id','customer_name','user','location','pilot_name'])->get();
                }
            }
        }
        elseif($request->page == 'delivery_order')
        {
            if ($request->get('query')) {
                $query = $request->get('query');
                if($query == "all")
                {
                    $data =Orders_d::with(['WaitOrders','Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('state','0')
                        ->where('d_order',$day_now)
                        ->select(['total','order_id','customer_name','user','location','pilot_name'])->get();
                }else{
                    $data =Orders_d::with(['WaitOrders','Locations'])
                        ->where('branch_id',Auth::user()->branch_id)
                        ->where('op','Delivery')
                        ->where('state','0')
                        ->where('d_order',$day_now)
                        ->where('pilot_id', 'LIKE', '%' . $query . "%")
                        ->select(['total','order_id','customer_name','user','location','pilot_name'])->get();
                }
            }
        }
        return response()->json($data);
    }
    public function Save_hold_delivery(Request $request)
    {
        date_default_timezone_set('Africa/Cairo');
        $day_now = $this->CheckDayOpen();
        $time_now = date('H:i');
        $branch = Auth::user()->branch_id;
        $order = 0;
        if($request->time == null){
            return response()->json(['status'=>'time']);
        }
        if($request->order_id == null){
            return response()->json(['status'=>'order']);
        }
        if(Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order_id,'op'=>'Delivery','customer_name'=>null])->count() > 0){
            return response()->json(['status'=>'none_customer']);
        }
        if(empty($request->date))
        {
          $request->date = $day_now;
        }
      $data = Orders_d::where('branch_id',$branch)
          ->where('order_id',$request->order_id)
          ->update([
              'time_hold_list' =>$request->time,
              'date_holde_list' =>$request->date,
              'to_pilot'=>0,
              'pilot_account'=>0,
              'hold_list' => 1
          ]);
        if($request->time != null && $request->order_id != null && $data)
            return response()->json(['status'=>'true']);
    }
    public function take_order_hold(Request $request)
    {
        $branch = Auth::user()->branch_id;
        // Git Operations
        $order = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order])->limit(1)->select(['op'])->first();
        $data_order = [];
        $data_wait = Wait_order::with(['Printer','Extra','Details'])->where(['order_id'=>$request->order])->get();
        $orderedItems = collect($data_wait)->sortBy('printers');
        $data_wait = $orderedItems->toArray();
        switch ($order->op) {
            case 'Delivery':
                {
                    $order = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order])->limit(1)
                        ->update([
                            'to_pilot'=>1,
                            'take_order'=>1,
                            'date_holde_list'=>null,
                            'time_hold_list'=>null,
                            'hold_list' => 0,
                        ]);
                    $wait = Wait_order::where(['branch_id'=>$branch,'order_id'=>$request->order])
                        ->update([
                            'pick_up' => 1
                        ]);
                    if($order && $wait)
                    {
                        return response()->json(['status'=>'true']);
                    }
                }
                break;
            case 'TO_GO':
                {
                    $order = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order])->limit(1)
                        ->update([
                            'date_holde_list'=>null,
                            'time_hold_list'=>null,
                            'hold_list' => 0,
                            'state'  => 0,
                            'delivery_order' => 1,
                            'take_order'=>1
                        ]);
                    $wait = Wait_order::where(['branch_id'=>$branch,'order_id'=>$request->order])
                        ->update([
                            'pick_up' => 1
                        ]);
                    if($order && $wait)
                    {
                        return response()->json(['status'=>'true']);
                    }
                }break;
        }
    }
    public function Togo_Order()
    {
        $this->CheckLastOrder();
            date_default_timezone_set('Africa/Cairo');
            $time_now = date(' H:i');
            $day_now = $this->CheckDayOpen();
            $pilots = User::where('branch_id',Auth::user()->branch_id)
                ->where('job_id',3)
                ->get();
            $orders = [];
            $orders = Orders_d::with(['WaitOrders','Locations'])
                ->where('branch_id',Auth::user()->branch_id)
                ->where('op','TO_GO')
                ->where('state','0')
                ->where('delivery_order','1')
                ->where('d_order',$day_now)
                ->select(['t_order','serial_shift','order_id','customer_name','user','location','pilot_name','pilot_id','discount','discount_type','total'])
                ->get();
            $locations = [];
            $pilots_o    = [];
            $countrer = 0;
            $flag = 0;
            $countr_p = 0;
            $flag_p = 0;
            $op_cal  = 'TO_GO';
            $alltaxandservice = [];
            $counter_tax = 0;
            if($orders->count() > 0){
                $taxandservice = $this->calculate_taxandservice($op_cal , $orders[0]->order_id);
                $alltaxandservice[$orders[0]->order_id] = [
                    'total'          =>$taxandservice[0]['total'],
                    'tax_ratio'      =>$taxandservice[0]['tax_ratio'],
                    'tax'            =>$taxandservice[0]['tax'],
                    'service_ratio'  =>$taxandservice[0]['service_ratio'],
                    'service'        =>$taxandservice[0]['service'],
                    'discount'       =>$taxandservice[0]['discount'],
                ];
            }

            $del_noti          = $this->Delivery();
            $del_noti_to_pilot = $this->Delivery_to_pilot();
            $del_noti_pilot    = $this->Delivery_pilot();
            $del_noti_hold     = $this->Delivery_hold();
            $to_noti_hold      = $this->TOGO_hold();
            return view('menu.togo_order',
                compact([
                    'to_noti_hold',
                    'pilots_o',
                    'locations',
                    'orders',
                    'del_noti',
                    'del_noti_to_pilot',
                    'del_noti_pilot',
                    'del_noti_hold',
                    'pilots'
                ]));

    }
    public function hold_list_togo()
    {
        $this->CheckLastOrder();
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $day_now = $this->CheckDayOpen();
        $pilots = User::where('branch_id',Auth::user()->branch_id)
        ->where('job_id',3)
        ->get();
        $orders = Orders_d::with(['WaitOrders','Locations'])
            ->where('branch_id',Auth::user()->branch_id)
            ->where('op','TO_GO')
            ->where('hold_list','1')
            ->where('d_order',$day_now)
            ->select(['t_order','order_id','customer_name','user','pilot_name','pilot_id','discount','discount_type','total','time_hold_list','date_holde_list'])
            ->get();
        $locations = [];
        $pilots_o    = [];
        $countrer = 0;
        $flag = 0;
        $countr_p = 0;
        $flag_p = 0;
        $op_cal  = 'TO_GO';
        $alltaxandservice = [];
        $counter_tax = 0;
        foreach($orders as $x)
        {
            $taxandservice = $this->calculate_taxandservice($op_cal , $x->order_id);
            $alltaxandservice[$x->order_id] = [
                'total'          =>$taxandservice[0]['total'],
                'tax_ratio'      =>$taxandservice[0]['tax_ratio'],
                'tax'            =>$taxandservice[0]['tax'],
                'service_ratio'  =>$taxandservice[0]['service_ratio'],
                'service'        =>$taxandservice[0]['service'],
                'discount'       =>$taxandservice[0]['discount'],
            ];
        }

        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        return view('menu.hold_togo',
            compact([
                'alltaxandservice',
                'to_noti_hold',
                'pilots_o',
                'locations',
                'orders',
                'del_noti',
                'del_noti_to_pilot',
                'del_noti_pilot',
                'del_noti_hold',
                'pilots'
            ]));
    }
    public function done_order_delivery(Request $request)
    {
        $branch  = Auth::user()->branch_id;
        $updateorder = Orders_d::limit(1)->where('order_id',$request->order_id)
        ->update([
            'state'          => 0,
            'pilot_account'  => 0,
            'delivery_order' => 1,
        ]);
        if($updateorder){
            $Orders = Wait_order::where('branch_id',$branch)
                ->where('order_id',$request->order)
                ->update([
                    'state'          => 0,
                ]);

            return response()->json(['status' => 'true']);
            }
    }
    public function takeOrderHold(Request $request){
        $orders = Orders_d::where(['hold_list'=>1])->get();
        date_default_timezone_set('Africa/Cairo');
        $time = date("Y-m-d h:i:sa");
        $time_stamp = strtotime($time);
        $time = strtotime($time);
        $falg = 0;
        $other = Others::where(['branch'=>$this->GetBranch()])->select(['display_modify'])->first();
        foreach ($orders as $order){
            $date = $order->date_holde_list . ' ' . $order->time_hold_list;
            $order_stamp = strtotime($date) - 3600;
            if($time_stamp >= $order_stamp) {
                if($other->display_modify == 1) {
                    $falg  = 1;
                    $cuurentOrder = Orders_d::find($order->id);
                    if($cuurentOrder->op == 'Delivery'){
                        $cuurentOrder->to_pilot = 1;
                    }
                    $cuurentOrder->hold_list = 0;
                    $cuurentOrder->take_order = 1;
                    $cuurentOrder->save();
                    $wait = Wait_order::where(['order_id'=>$cuurentOrder->order_id])->update([
                        'status_take'=>1
                    ]);
                    $order_print = array('branch'=>$this->GetBranch(),'order_id'=>$cuurentOrder->order_id,'type'=>1,'no_copies'=>1,'val_type'=>'New');
                    $this->SerialShift($cuurentOrder->order_id);
                    $this->AddTotalWait($cuurentOrder->order_id);
                    $this->AddTotalOrder($cuurentOrder->op,$cuurentOrder->order_id);
                    $this->OrderPrint($order_print);
                    if ($cuurentOrder->op == 'TO_GO'){
                        $orderCurrent = Orders_d::find($cuurentOrder->id);
                        $updateorder = Orders_d::limit(1)->where('order_id',$cuurentOrder->order_id)
                            ->update([
                                'no_print'        => 1,
                                'cashier'         => $this->GetUser(),
                                'method'          => 'cash',
                                'devcashier'      => $cuurentOrder->dev_id,
                                'tip'             => '0',
                                'cash'            => $orderCurrent->total,
                                'visa'            => 0,
                                't_closeorder'    =>$this->Get_Time(),
                                'r_bank'          =>0,
                                'hos'             =>0,
                                'state'           =>0,
                                'shift'           => $this->Shift(),
                                'd_order'         => $this->CheckDayOpen(),
                            ]);
                        $device_print = Device::limit(1)->where(['branch_id'=>$this->GetBranch(),'id_device'=>$cuurentOrder->dev_id])->first();
                        $type_check = 3;
                        $ex = ToGo::limit(1)->where(['branch'=>$this->GetBranch()])->select(['printer','invoice_copies'])->first();
                        $printer = $device_print->printer_invoice;
                        $no_copies = $ex ->invoice_copies;
                        $order_print = array(
                            'branch'=>$this->GetBranch(),
                            'order_id'=>$cuurentOrder->order_id,
                            'type'=>$type_check,
                            'no_copies'=>$no_copies ,
                            'printer'=>$printer
                        );
                        $this->OrderPrint($order_print);
                    }
                    if($falg == 1){
                        return ['status'=>true];
                    }else{
                        return ['status'=>false];
                    }
                }else{
                    if(isset($request->status)){
                        if($request->status == true){
                            $falg  = 1;
                            $cuurentOrder = Orders_d::find($order->id);
                            if($cuurentOrder->op == 'Delivery'){
                                $cuurentOrder->to_pilot = 1;
                            }
                            $cuurentOrder->hold_list = 0;
                            $cuurentOrder->take_order = 1;
                            $cuurentOrder->save();
                            $wait = Wait_order::where(['order_id'=>$cuurentOrder->order_id])->update([
                                'status_take'=>1
                            ]);
                            $order_print = array('branch'=>$this->GetBranch(),'order_id'=>$cuurentOrder->order_id,'type'=>1,'no_copies'=>1,'val_type'=>'New');
                            $this->SerialShift($cuurentOrder->order_id);
                            $this->AddTotalWait($cuurentOrder->order_id);
                            $this->AddTotalOrder($cuurentOrder->op,$cuurentOrder->order_id);
                            $this->OrderPrint($order_print);
                            if ($cuurentOrder->op == 'TO_GO'){
                                $orderCurrent = Orders_d::find($cuurentOrder->id);
                                $updateorder = Orders_d::limit(1)->where('order_id',$cuurentOrder->order_id)
                                    ->update([
                                        'no_print'        => 1,
                                        'cashier'         => $this->GetUser(),
                                        'method'          => 'cash',
                                        'devcashier'      => $cuurentOrder->dev_id,
                                        'tip'             => '0',
                                        'cash'            => $orderCurrent->total,
                                        'visa'            => 0,
                                        't_closeorder'    =>$this->Get_Time(),
                                        'r_bank'          =>0,
                                        'hos'             =>0,
                                        'state'           =>0,
                                        'shift'           => $this->Shift(),
                                        'd_order'         => $this->CheckDayOpen(),
                                    ]);
                                $device_print = Device::limit(1)->where(['branch_id'=>$this->GetBranch(),'id_device'=>$cuurentOrder->dev_id])->first();
                                $type_check = 3;
                                $ex = ToGo::limit(1)->where(['branch'=>$this->GetBranch()])->select(['printer','invoice_copies'])->first();
                                $printer = $device_print->printer_invoice;
                                $no_copies = $ex ->invoice_copies;
                                $order_print = array(
                                    'branch'=>$this->GetBranch(),
                                    'order_id'=>$cuurentOrder->order_id,
                                    'type'=>$type_check,
                                    'no_copies'=>$no_copies ,
                                    'printer'=>$printer
                                );
                                $this->OrderPrint($order_print);
                            }
                            if($falg == 1){
                                return ['status'=>true];
                            }else{
                                return ['status'=>false];
                            }
                        }
                    }
                    return ['status'=>'check','order'=>$order->order_id];
                }
            }
        }

    }
    public function takeOrderHoldByOrders(Request $request){
        return $request;
    }
}

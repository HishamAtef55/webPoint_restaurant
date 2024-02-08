<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\ActionTables;
use App\Models\Details_Wait_Order;
use App\Models\Delavery;
use App\Models\Group;
use App\Models\Printers;
use App\Models\User;
use App\Models\Item;
use App\Models\Locations;
use App\Models\TransferUsers;
use App\Models\OrdersM;
use App\Models\Orders_d;
use App\Models\Service_tables;
use App\Models\Reservation;
use App\Models\Sub_group;
use App\Models\Table;
use App\Models\menu;
use App\Models\Branch;
use App\Models\Wait_order;
use App\Models\TablesMerge;
use App\Models\Checkdevice;
use App\Models\LogTransfer;
use App\Models\discounts;
use App\Traits\All_Notifications_menu;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Traits\All_Functions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TablesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Functions;
    use All_Notifications_menu;
    public function new_order($table_id)
    {
        $table= substr($table_id,6);
        $this->CheckDay();
        $this->removeActionTable();
        $operation = 'Table';
        $this->CheckLastOrder();
        $usercheck = Auth::user();
        $branch = Auth::user()->branch_id;
        $user_id = Auth::user()->id;
        $to_noti_hold     = $this->TOGO_hold();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $operation= '';
        $id_active_menu = null;
        $no_print = 0;
        $op_log = $operation;
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
        if($id_active_menu == null){
            return redirect()->back();
        }
        $subgroup = Group::where('branch_id',$branch)
        ->where('menu_id',$id_active_menu)
        ->get();


        switch($table_id)
        {
            case "TO_GO":
                {
                    $operation = "TO_GO";
                    // $new_order = $this->generate_TO_order($branch);
                    // Select All Sub Group in This Branch
                    // Select All Wait Order In This table
                    // $OrdersM   = Wait_order::with(['Details','Extra'])->where('state',0)->where('table_id',$new_order)->get();
                    // Get Discount
                    $discount_ratio = discounts::where('branch_id',$branch)
                        -> where('type','Ratio')
                        ->get();
                    $discount_value = discounts::where('branch_id',$branch)
                        -> where('type','Value')
                        -> get();

                    $locations = Locations::with('Branch')->where('branch_id',$branch)->get();

                    $dis_name = '';
                    $dis_val  = 0;
                    $dis_type  ='Ratio';
                    $dis = 0;
                    $op_cal  = 'TO_GO';
                    $new_order = '';
                    $state_ser = 0;
                    $state_tax = 0;
                    $delivery = 0;
                    $mincharge = 0 ;
                    $capten    = 0;
                    $customer = 0;
                    $gust     = 0;
                    $taxandservice = $this->calculate_taxandservice($op_cal , $new_order);
                    $printers = Printers::where(['active'=>'1'])->get();
                    return view('menu.menu',compact(['printers','no_print','to_noti_hold','gust','mincharge','capten','customer','delivery','state_tax','state_ser','taxandservice','locations','dis','dis_type','dis_val','dis_name','del_noti','del_noti_to_pilot','del_noti_pilot','del_noti_hold','operation','menus','subgroup','discount_value','discount_ratio']));
                }
                break;
            case "Delivery":
                {
                    $operation = "Delivery";
                    // $new_order = $this->generate_D_order($branch);
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
                    // Select All Wait Order In This table
                    // $OrdersM   = Wait_order::with(['Details','Extra'])->where('table_id',$new_order)->get();
                    // Get Discount
                    $discount_ratio = discounts::where('branch_id',$branch)
                        -> where('type','Ratio')
                        ->get();
                    $discount_value = discounts::where('branch_id',$branch)
                        -> where('type','Value')
                        -> get();
                    $locations = Locations::with('Branch')->where('branch_id',$branch)->get();
                    $dis_name = '';
                    $dis_val  = 0;
                    $dis_type  ='Ratio';
                    $dis = 0;
                    $op_cal  = 'Delivery';
                    $new_order = '';
                    $state_ser = 0;
                    $state_tax = 0;
                    $mincharge = 0 ;
                    $capten    = 0;
                    $customer = 0;
                    $gust     = 0;
                    $taxandservice = $this->calculate_taxandservice($op_cal , $new_order);
                    $printers = Printers::where(['active'=>'1'])->get();
                    return view('menu.menu',compact(['printers','no_print','to_noti_hold','gust','mincharge','capten','customer','state_tax','state_ser','taxandservice','dis','dis_type','dis_val','dis_name','locations','del_noti','del_noti_to_pilot','del_noti_pilot','del_noti_hold','operation','menus','subgroup','discount_value','discount_ratio']));
                }
                break;
            default:
            {
                $op_log = $table;
                $userTable  = Auth::user()->id;
                $userName   = Auth::user()->name;
                $branchUser = Auth::user()->branch_id;
                $actionTable = array('table'=>$table , 'branch'=>$branchUser , 'user_id'=>$userTable , 'user'=>$userName);
                $branchUser = Auth::user()->branch_id;
                if(ActionTables::where(['table'=>$table,'branch'=>$branchUser])->count() > 0){
                    $data_back = ActionTables::limit(1)->where(['table'=>$table,'branch'=>$branchUser])->first();
                    $msg =  $data_back->user . " Use This Table";

                    return Redirect::back()->with('data_back', $msg);
                }
                if($usercheck->can('open-tables')){
                }else{
                    if(Table::where(['branch_id'=>$branch,'table_id'=>$table,'state'=>'0'])->count() == 0){
                        if(Table::where(['branch_id'=>$branch,'table_id'=>$table,'user_id'=>$user_id])->count() == 0){
                            return Redirect::back()->with('msg', 'No operations can be performed on this table');
                        }
                    }
                }
                $operation = 'Table';
                $table= substr($table_id,6);
                $delivery = 0;
                $branch   = Table::select(['branch_id','min_charge'])
                    ->where('branch_id',Auth::user()->branch_id)
                    ->where('number_table',$table)->get() -> first();

                // $new_order = $this->get_table_order($table_id,$branch->branch_id);
                $min_charge = $branch->min_charge;
                // Select Menu Of Branch
                $id_active_menu = 0;
                $menus = menu::where('branch_id',$branch->branch_id)
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
                // $subgroup = Group::where('branch_id',$branch->branch_id)
                // ->where('menu_id',$id_active_menu)
                // ->get();
                // Select All Wait Order In This table
                $Orders   = Wait_order::with(['Details','Extra','Without_m'])
                    ->where('branch_id',$branch->branch_id)
                    ->where('table_id',$table)
                    ->where('state',1)
                    ->get();
                $dis_name    = '';
                $dis_val     = 0;
                $dis_type    ='Ratio';
                $dis         = 0;
                $mincharge   = 0 ;
                $capten      = 0;
                $customer    = 0;
                $gust        = 0;
                $no_print    = 0;
                if($Orders->count() == 0)
                {
                    $new_order = '';
                    $state_ser = 0;
                    $state_tax = 0;
                    $op_cal  = 'Table';
                    $taxandservice = $this->calculate_taxandservice($op_cal , $new_order);

                }else{
                    $new_order = $Orders[0]->order_id;
                    $order_dis = Orders_d::limit(1)->where(['branch_id'=>$branch->branch_id,'order_id'=>$new_order])
                        ->select(['no_print','gust','min_charge','user','customer_name','discount','discount_name','discount_type','state_service','state_tax','total_discount','created_at'])->first();
                    $dis_name = $order_dis->discount_name;
                    $dis_type = $order_dis->discount_type;
                    $dis       = $order_dis->discount;
                    $state_ser = $order_dis->state_service ;
                    $state_tax = $order_dis->state_tax;
                    $mincharge = $order_dis->min_charge;
                    $capten    = $order_dis->user;
                    $customer  = $order_dis->customer_name;
                    $gust      = $order_dis->gust;
                    $no_print  = $order_dis->no_print;
                    $wait = Wait_order::where('order_id',$new_order)
                        ->select(['total','total_extra','price_details','total_discount','created_at'])->get();
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
                    $op_cal  = 'Table';
                    $taxandservice = $this->calculate_taxandservice($op_cal , $new_order);
                }
                $discount_ratio = discounts::where('branch_id',$branch->branch_id)
                    -> where('type','Ratio')
                    ->get();
                $discount_value = discounts::where('branch_id',$branch->branch_id)
                    -> where('type','Value')
                    -> get();
                if($dis_type == null)
                {
                    $dis_type = 'Ratio';
                    $dis = '0';
                }
                $this->addActionTable($actionTable);
                $printers = Printers::where(['active'=>'1'])->get();
                return view('menu.menu',compact(['printers','no_print','to_noti_hold','gust','mincharge','capten','customer','delivery','state_tax','state_ser','taxandservice','dis','dis_type','dis_val','dis_name','new_order','Orders','del_noti','del_noti_to_pilot','del_noti_pilot','del_noti_hold','operation','menus','subgroup','table','discount_value','discount_ratio','min_charge']));
            }
        }
    }
    public function get_total_table(Request $request)
    {
        $branch = Auth::user()->branch_id;
        $order = Orders_d::limit(1)->where(['branch_id'=>$branch,'table'=>$request->tableNumber,'state'=>'1'])
            ->select(['total','user','gust','order_id'])->first();
        $this->reCalcOrder($order->order_id);
        $order = Orders_d::limit(1)->where(['branch_id'=>$branch,'table'=>$request->tableNumber,'state'=>'1'])
            ->select(['total','user','gust','order_id'])->first();
        return response()->json(['total'=>$order->total,'order'=>$order->order_id ,'captain'=>$order->user, 'gust'=>$order->gust]);
    }

    // SAve Merge Tables
    public function Save_merge(Request $request)
    {
        $user_id = Auth::user()->id;
        // check master
        if(Table::limit(1)->where(['number_table'=>$request->master_table,'user_id'=>$user_id])->count() == 0){
            return  response()->json(['status'=>'false','table'=>$request->master_table,'msg'=>'You do not have transactions on the table']);
        }
        $branch = Auth::user()->branch_id;
        //Create NEw Data
        $update_table = Table::where('branch_id',$branch)->where('number_table',$request->master_table)
        ->update([
            'master' => 0
        ]);
        $delete = Table::where('branch_id',$branch)->where('follow',$request->master_table)
        ->update([
            'merged' => 0 ,
            'follow' => 0 ,
        ]);
        if(empty($request->slave_tables))
        {
            return response()->json([
                'status' => false,
                'master' => $request->master_table
            ]);
        }else
        {
            foreach($request->slave_tables as $update)
            {
                if(Table::limit(1)->where(['branch_id'=>$branch,'number_table'=>$update['id'],'user_id'=> '0'])->count() == 1){
                    $update = Table::where('branch_id',$branch)->where('number_table',$update['id'])
                        ->update([
                            'merged' => 1,
                            'follow' => $request->master_table
                        ]);
                }else{
                    return  response()->json(['status'=>'false','msg'=>'You do not have transactions on the table','table'=>$update['id']]);

                }

            }
            $update = Table::where('number_table',$request->master_table)
            ->update([
                'master' => 1
            ]);
            return response()->json([
                'status' => true
            ]);
        }

    }

    public function get_users(Request $request){
        $branch = Auth::user()->branch_id;
        $user   = Auth::user()->id;
        $users = User::where(['branch_id'=>$branch,])->get();
        return response()->json(['data'=>$users,'user'=>$user]);
    }

    public function transfer_users(Request $request){
        $user = Auth::user()->id;
        date_default_timezone_set('Africa/Cairo');
        $time_now = date('H-i');
        $day_now = $this->CheckDayOpen();
        $branch = Auth::user()->branch_id;
        $data = TransferUsers::create([
            'n_user'  =>$request->userId,
            'order'   =>$request->order,
            'table'   =>strval($request->table),
            'date'    =>$day_now,
            'time'    =>$time_now,
            'status'  =>'Wait',
            'c_user'  =>$user,
            'branch'  =>$branch,
            'note'    =>$request->note
        ]);
        if($data){
            return response()->json(['status'=>'true']);
        }
    }

    public function Get_Wait_Transfer(Request $request){
        date_default_timezone_set('Africa/Cairo');
        $branch = Auth::user()->branch_id;
        $user = Auth::user()->id;
        $day_now = $this->CheckDayOpen();
        $noti = TransferUsers::with(['CurrentUser','NewUser'])->where(['n_user'=>$user,'status'=>'Wait','date'=>$day_now])
        ->orWhere(['c_user'=>$user])
        ->where(['date'=>$day_now])
        ->select(['c_user','order','table','status','n_user','id','note'])
        ->orderBy('id','DESC')
        ->get();
        return $noti;
    }

    public function opration_transfer(Request $request){
        $user    = Auth::user()->name;
        $user_id = Auth::user()->id;
        $date = $this->Get_Date();
        $time = $this->Get_Time();
        $branch = $this->GetBranch();

        $get_order = Orders_d::limit(1)->where(['order_id'=>$request->order])->select(['table','user'])->first();
        $from = $get_order->user;
        switch($request->status){
            case 'accepte_noti':{
                $data = Orders_d::limit(1)->where(['order_id'=>$request->order])
                    ->update([
                        'user'    => $user,
                        'user_id' => $user_id,
                    ]);
                $update_wait = Wait_order::where('order_id',$request->order)
                    ->update([
                        'user'    => $user,
                        'user_id' => $user_id,
                    ]);
                $update_table = Table::limit(1)->where(['branch_id'=>$branch,'number_table'=>$get_order->table])->update([
                    'user_id' =>$user_id,
                    'user'=>$user
                ]);
                if($data){
                    $order = TransferUsers::limit(1)->where(['id'=>$request->noti_id])->update([
                        'status' => 'Accepted',
                    ]);
                    $savelog = LogTransfer::create([
                        'branch'   =>$branch,
                        'date'     =>$date,
                        'time'     =>$time,
                        'from'     =>$from,
                        'to'       =>$user,
                        'waiter'   =>$get_order->table,
                        'type'     =>'Transfer',
                    ]);
                    return response()->json(['status'=>'true']);
                }
            }break;
            case 'reject_noti':{
                $data = TransferUsers::limit(1)->where(['id'=>$request->noti_id])->update([
                    'status' => 'Rejected',
                ]);
                if($data){
                    return response()->json(['status'=>'false']);
                }
            }break;
        }
    }

    public function get_reservation(Request $request){
        $branch = Auth::user()->branch_id;
        $res = Reservation::where(['branch_id'=>$branch , 'table_id'=>$request->table , 'status' => '0'])
            ->get();
        return response()->json($res);
    }

    public function del_reservation(Request $request){
        $user = Auth::user()->id;
        $res = Reservation::where(['id'=>$request->resid])
            ->update([
                'status' => 1,
                'user_del' => $user,
            ]);
        return response()->json(['ststus' => 'true']);
    }

}

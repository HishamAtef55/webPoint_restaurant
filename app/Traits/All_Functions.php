<?php
namespace App\Traits;
use App\Models\ActionTables;
use App\Models\CloseShiftDaily;
use App\Models\Item;
use App\Models\LogInfo;
use App\Models\Orders_d;
use App\Models\Others;
use App\Models\TransferUsers;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\CloseShiftGroup;
use App\Models\CloseShift;
use App\Models\Days;
use App\Models\FIRST_REP;
use App\Models\Order_Print;
use App\Models\Table;
use App\Models\Group;
use App\Models\Wait_order;
use App\Models\Service_tables;
use App\Models\Delavery;
use App\Models\ToGo;
use App\Models\Details_Wait_Order;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\All_Functions;
use App\Traits\All_Notifications_menu;
use App\Models\Details_Wait_Order_m;
use App\Models\Extra_wait_order;
use App\Models\Extra_wait_order_m;
use App\Models\Orders_m;
use App\Models\Void_d;
use App\Models\Void_m;
use App\Models\Wait_order_m;
use App\Models\SerialCheck;
use App\Models\SerialShift;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Redirect;



Trait All_Functions
{
    public function Get_Date(){
        date_default_timezone_set('Africa/Cairo');
        return $day_now  = date('Y-m-d');
    }
    public function Get_Time(){
        date_default_timezone_set('Africa/Cairo');
        return $time_now = date('H-i');
    }
    public function CheckDayOpen(){
        date_default_timezone_set('Africa/Cairo');
        $date      = date('Y-m-d');
        $time      = date(' H-i');
        $branch    = Auth::user()->branch_id;
        $shift_all = Shift::limit(1)->where(['branch_id'=>$branch,'status'=>1])->first();
        $shift     =  $shift_all->shiftid;
        $open_day = '';
        if(Days::where(['branch'=>$branch,'active'=>1])->count() > 0){
            $day = Days::limit(1)->where(['branch'=>$branch,'active'=>1])->select(['date'])->first();
            $open_day = $day->date;
        }else{
            if(Days::where(['branch'=>$branch,'date'=>$date])->count() > 0){
                $reactive_day = Days::where(['branch'=>$branch,'date'=>$date])->update(['active'=>1]);
                $day = Days::limit(1)->where(['branch'=>$branch,'active'=>1])->select(['date','last_shift'])->first();
                $shift_all = Shift::where(['branch_id'=>$branch])->get();
                $update = Shift::limit(1)->where(['branch_id'=>$branch,'status'=>1])->update(['status'=>0]); // Remove last Shift is OPen
                $update = Shift::limit(1)->where(['branch_id'=>$branch,'shiftid'=>$shift])->update(['status'=>1]); // Reactive last Shift is OPen
                $open_day = $day->date; // Return Active Date
            }else{
                $create = Days::create([
                    'branch'    =>$branch,
                    'date'      =>$date,
                    'time_open' =>$time,
                    'active'    =>1,
                    'last_shift'=>$shift
                ]);
            }
            $open_day = $date;
        }
        return $open_day;
    }
    function saveimage($photo , $folder)
    {
        $file = $photo -> getClientOriginalExtension();
        $no_rand = rand(10,1000);
        $file_name = time() . $no_rand .  '.' . $file;
        $photo -> move($folder , $file_name);
        return $file_name;
    }
    function get_table_order($table_id,$branch)
    {
        $new_order = 0;
        if(\App\Models\OrdersM::where('branch_id',$branch)->where('op',$table_id)->limlit->count() >0)
        {
            if(\App\Models\Wait_order::where('branch_id',Auth::user()->branch_id)->where('op',$table_id)->count() > 0)
            {
                $data_w = \App\Models\Wait_order::where('branch_id',Auth::user()->branch_id)->where('op',$table_id)->get()->last();
                $new_order = $data_w->number_of_order ;
            }else
            {
                $check_order = \App\Models\OrdersM::where('branch_id',$branch)->where('branch_id',Auth::user()->branch_id)
                    ->where('op',$table_id)->select(['order_no_table'])->get();
                $table = substr($table_id,6);
                $length_t = strlen($table) + 2;
                $new_array =[];
                $x = 0;
                foreach($check_order as $key => $order)
                {
                    $id= substr($order['order_no_table'],$length_t);
                    $new_array[$x]=$id;
                    $x++;
                }
                $max_val = max($new_array)+1;
                $new_order = 'T'.$table.'-'.$max_val;
            }
        }
        else
        {
            $table = substr($table_id,6);
            $new_order = 'T'.$table.'-1';
        }

        return $new_order;
    }
    function get_device_order($dev,$branch)
    {
        $new_order_Dev = 0 ;
        if(\App\Models\OrdersM::where('branch_id',$branch)->count()>0)
        {
            $data= \App\Models\OrdersM::where('branch_id',$branch)->select(['order_id'])->get();
            $dev_len = strlen($dev) + 1;
            $new_array =[];
            $x = 0;
            foreach($data as $key => $order)
            {
                $id= substr($order['order_id'],$dev_len);
                $new_array[$x]=$id;
                $x++;
            }
            $max_val = max($new_array)+1;

            $new_order_Dev = $dev .'-'. $max_val;
        }else{
            $new_order_Dev = $dev .'-'. 1;
        }
        return $new_order_Dev;
    }
    function Increase_Sub_Order($serial,$branch)
    {
        $new_sub_num_order = 0;
        if(\App\Models\Wait_order::where('branch_id',$branch)->where('order_id',$serial)->limit(1)->count() > 0)
        {
            $new_sub_num_order = \App\Models\Wait_order::where('branch_id',$branch)->where('order_id',$serial)->max('sub_num_order');
            $new_sub_num_order ++;
        }else{
            $new_sub_num_order ++;
        }
        return $new_sub_num_order ;
    }
    function Add_item($name,$chick_name,$slep_name,$price,$takeaway_price,$dellvery_price,$wight,$unit,$barcode,$file,$printers,$branch,$menu,$group,$subgroup)
    {
        $data = \App\Models\Item::create
        ([
            'name'                  =>$name,
            'chick_name'            =>$chick_name,
            'slep_name'             =>$slep_name,
            'price'                 =>$price,
            'takeaway_price'        =>$takeaway_price,
            'dellvery_price'        =>$dellvery_price,
            'wight'                 =>$wight,
            'unit'                  =>$unit,
            'barcode'               =>$barcode,
            'image'                 =>$file,
            'printers'              =>$printers,
            'branch_id'             =>$branch,
            'menu_id'               =>$menu,
            'group_id'              =>$group,
            'sub_group_id'          =>$subgroup,
        ]);
    }
    function open_table($table_id)
    {
        $branch = Auth::user()->branch_id;
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $check_order = \App\Models\Wait_order::where(['branch_id'=>$branch,'table_id'=>$table_id])->get();
        if($check_order)
        {
            $data_state_table = \App\Models\Table::where(['branch_id'=>$branch,'number_table'=>$table_id])
                ->update([
                    'state'       =>1,
                    'booked_up'   =>0,
                    'user'        =>Auth::user()->name,
                    'user_id'     =>Auth::user()->id,
                    'table_open'  =>$time_now,
                ]);
        }
    }
    function close_table($table_id)
    {
        $branch = Auth::user()->branch_id;
        if(\App\Models\Wait_order::where(['branch_id'=>$branch,'table_id'=>$table_id,'state'=>1])->count() > 0)
        {

        }else{
            $update_state = \App\Models\Table::where(['branch_id'=>$branch,'number_table'=>$table_id])
                ->update([
                    'state'      =>0,
                    'user'       =>0,
                    'user_id'    =>0,
                    'table_open' =>0,
                ]);
        }

    }
    function get_new_serial($branch , $order , $dev)
    {
        $new_serial = 0;
        $branch_dev = $branch . $dev;
        if(empty($order))
        {
            if(SerialCheck::limit(1)->where(['branch_id'=>$branch ,'branch_dev'=>$branch_dev])->count() > 0)
            {
                $last = SerialCheck::where(['branch_id'=>$branch ,'branch_dev'=>$branch_dev])->max('serial');
                $serial = $last + 1;
                $new_serial = $branch . $dev . $serial;
                SerialCheck::create([
                    'branch_id'  =>$branch,
                    'serial'     =>$serial,
                    'order'      =>$new_serial,
                    'branch_dev' =>$branch_dev
                ]);
            }else{
                $new_serial = $branch . $dev . 1;
                SerialCheck::create([
                    'branch_id'  =>$branch,
                    'serial'     =>1,
                    'order'      =>$new_serial,
                    'branch_dev' =>$branch_dev
                ]);
            }
        }else{
            $new_serial =  $order;
        }
        return $new_serial;
    }
    function calculate_taxandservice($op , $order_id){
        $branch               = Auth::user()->branch_id;
        $taxandservice        = [];
        $items                = [];
        $order                = [];
        $total_order          = 0;
        $discount_tax_service = 0;
        $tax                  = 0;
        $service_ratio        = 0;
        $total_ser            = 0;
        $service_cal          = 0;
        $flag                 = 0;
        $delivery             = 0;
        $dis_update = 0;
        $total_order_desall = 0;
        $total_order_with = 0;
        if(empty(!$order_id))
        {
            $items = Wait_order::where(['order_id'=>$order_id])
                ->select(['all_total','total','total_extra','price_details','total_discount'])
                ->get();
            $order = Orders_d::limit(1)
                ->where(['order_id'=>$order_id])
                ->select(['total_discount','discount','discount_type','service_ratio','tax_ratio','discount_tax_service','delivery'])
                ->first();
            $dis_update           = $order->total_discount;
            if(Orders_d::limit(1)->where(['order_id'=>$order_id])->count() > 0){
                $flag = 1;
                $delivery = $order->delivery;
            }
        }
        switch($op)
        {
            case 'Table':{
                if($flag == 1)
                {
                    $discount_tax_service = $order->discount_tax_service;
                    $tax                  = $order->tax_ratio;
                    $service_ratio        = $order->service_ratio;
                }else{
                    $check = Service_tables::limit(1)
                        ->where('branch',$branch)
                        ->select(['discount_tax_service','tax','service_ratio'])
                        ->first();
                    if($check){
                        $discount_tax_service = $check->discount_tax_service;
                        $tax                  = $check->tax;
                        $service_ratio        = $check->service_ratio;
                    }
                }
            }break;
            case 'Delivery':{
                if($flag == 1)
                {
                    $discount_tax_service = $order->discount_tax_service;
                    $tax                  = $order->tax_ratio;
                    $service_ratio        = $order->service_ratio;
                }else{

                    $check = Delavery::limit(1)
                        ->where('branch',$branch)
                        ->select(['discount_tax_service','ser_ratio'])
                        ->first();
                    if($check){
                        $discount_tax_service = $check->discount_tax_service;
                        $tax                  = '0';
                        $service_ratio        = $check->ser_ratio;
                    }
                }
            }break;
            case 'TO_GO':{
                if($flag == 1)
                {
                    $discount_tax_service = $order->discount_tax_service;
                    $tax                  = $order->tax_ratio;
                    $service_ratio        = $order->service_ratio;
                }else{
                    $check = TOGO::limit(1)
                        ->where('branch',$branch)
                        ->select(['discount_tax_service','tax','service_ratio'])
                        ->first();
                    if($check){
                        $discount_tax_service = $check->discount_tax_service;
                        $tax                  = $check->tax;
                        $service_ratio        = $check->service_ratio;
                    }
                }
            }
        }
        if($discount_tax_service == 0)
        {
            foreach($items as $item)
            {
                $total_order = $total_order + $item->total + $item->total_extra + $item->price_details;
                $total_order_desall = $total_order_desall + $item->total_discount;
            }
            $service_cal = ($total_order * $service_ratio) / 100 ;
            $taxandservice[0]=
                [
                    'order_id'       =>$order_id,
                    'total'          =>$total_order - $total_order_desall,
                    'tax_ratio'      =>$tax,
                    'tax'            =>(($total_order + $service_cal + $delivery - $total_order_desall) * $tax) / 100,
                    'service_ratio'  =>$service_ratio,
                    'service'        =>$service_cal + $delivery,
                    'discount'       =>$discount_tax_service,
                ];
            return $taxandservice;
        }
        elseif($discount_tax_service == 1)
        {
            foreach($items as $item)
            {
                $total_order  = $total_order + $item->total + $item->total_extra + $item->price_details - $item->total_discount;
                $total_ser    = $total_ser + $item->total + $item->total_extra + $item->price_details;
            }
            if(empty($order))
            {
                $taxandservice[0]=[
                    'order_id'       =>0,
                    'total'          =>0,
                    'tax_ratio'      =>$tax,
                    'tax'            =>0,
                    'service_ratio'  =>$service_ratio,
                    'service'        =>0,
                    'discount'       =>$discount_tax_service,
                ];
                return $taxandservice;
            }
            else
            {
                $total_order_with = $total_order;
                if($order->discount_type == 'Ratio')
                {
                    $dis = ($total_order * $order->discount) / 100;
                    $total_order_with = $total_order - $dis;
                }
                elseif($order->discount_type == 'Value')
                {
                    $total_order  =  $total_order;
                    $total_order_with = $total_order - $order->total_discount;
                }
                $service_cal = ($total_ser * $service_ratio) / 100 ;
                $taxandservice[0]=
                    [
                        'order_id'       =>$order_id,
                        'total'          =>$total_order,
                        'tax_ratio'      =>$tax,
                        'tax'            =>(($total_order_with + $service_cal + $delivery) * $tax) / 100,
                        'service_ratio'  =>$service_ratio,
                        'service'        =>$service_cal + $delivery,
                        'discount'       =>$discount_tax_service,
                    ];
                $chcek_status = Orders_d::limit(1)
                    ->where(['order_id'=>$order_id])
                    ->select(['state_service','state_tax'])
                    ->first();
                if($chcek_status->state_service == 1){
                    $taxandservice[0]['service_ratio'] = 0;
                    $taxandservice[0]['service'] = 0;
                }
                if($chcek_status->state_tax == 1){
                    $taxandservice[0]['tax_ratio'] = 0;
                    $taxandservice[0]['tax'] = 0;
                }
                return $taxandservice;
            }
        }
    }
    function Shift(){
        $branch = Auth::user()->branch_id;
        $shift  = Shift::where(['branch_id'=>$branch,'status'=>1])->first();
        return $shift->shiftid;
    }
    function GetBranch(){
        return Auth::user()->branch_id;
    }
    function GetUser(){
        return Auth::user()->id;
    }
    function CloseDay(){
        $branch = $this->GetBranch();
        $date   = $this->CheckDayOpen();
        $time   = $this->Get_Time();
        $update = Days::limit(1)->where(['branch'=>$branch,'date'=>$date])->update([
            'active'=>0,
            'time_close'=>$time
        ]);
    }
    function OrderPrint($data){
        $order = Order_Print::create($data);
    }
    function CheckDay(){
        date_default_timezone_set('Africa/Cairo');
        $branch     = $this->GetBranch();
        $date       = $this->Get_Date();
        $date_open  = $this->CheckDayOpen();
        $time = date('H');
        $timeClose = Others::where('branch',$branch)->first();
        if($timeClose->close_day != null || $timeClose->close_day != ''){
            list($partH, $partM) = explode(':', $timeClose->close_day);
            $partM = $partM / 60 ;
            $partH += $partM;
            $close_date = $partH;
        }else{
            $close_date = '07';
        }
        $close_date;
        if($date != $date_open){
            if($time >= $close_date ){
                $this->EndDay();
            }
        }
    }
    public function ErrorShiftAnswer(){
        $branch = $this->GetBranch();
        $orders = Orders_d::where(['state'=>'1','branch_id'=>$branch])->get();
        $serial = SerialShift::where(['branch'=>$branch])->get();
        foreach($serial as $del){
            foreach ($orders as $order){
                if($del->order_id != $order->order_id){
                    SerialShift::limit(1)->where(['branch'=>$branch,'order_id'=>$del->order_id])->delete();
                }
            }
        }
    }
    public function EndDay(){
        $this->ErrorShiftAnswer();
        $branch = $this->GetBranch();
        $date   = $this->CheckDayOpen();
        $orders = Orders_d::where(['state' => '0','branch_id'=>$branch])->get();
        foreach ($orders as $order) {
            Orders_m::create([
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
                'discount' => $order->discount,
                'discount_name' => $order->discount_name,
                'discount_type' => $order->discount_type,
                'total_discount' => $order->total_discount,
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
            $wait = Wait_order::with(['Details', 'Extra'])->where(['order_id' => $order->order_id])->get();
            foreach ($wait as $wa) {
                $ins = Wait_order_m::create([
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
                    'd_order'=>$order->d_order,
                ]);
                foreach ($wa->details as $det){
                    Details_Wait_Order_m::create([
                        'number_of_order'=>$det->number_of_order,
                        'detail_id'=>$det->detail_id,
                        'price'=>$det->price,
                        'name'=>$det->name,
                        'wait_order_id'=>$ins->id,
                    ]);
                    $del = Details_Wait_Order::where('id',$det->id)->delete();
                }
                foreach ($wa->Extra as $ex){
                    $new_order = Extra_wait_order_m::create([
                        'extra_id'=>$ex->extra_id,
                        'wait_order_id'=>$ins->id,
                        'number_of_order'=>$ex->number_of_order,
                        'price'=>$ex->price,
                        'name'=>$ex->name,
                    ]);
                    $del = Extra_wait_order::where('id',$ex->id)->delete();
                }
                $del = Wait_order::where('order_id',$wa->order_id)->delete();
            }
            $del = Orders_d::where('order_id',$order->order_id)->delete();
        }
        // move the Void
        $voids = Void_d::get()->all();
        foreach ($voids as $void_d){
            Void_m::create([
                'order_id' => $void_d->order_id,
                'state' => $void_d->state,
                'item_id' => $void_d->item_id,
                'op' => $void_d->op,
                'table_id' => $void_d->table_id,
                'sub_num_order' => $void_d->sub_num_order,
                'moved' => $void_d->moved,
                'name' => $void_d->name,
                'quantity' => $void_d->quantity,
                'price' => $void_d->price,
                'total' => $void_d->total,
                'total_extra' => $void_d->total_extra,
                'price_details' => $void_d->price_details,
                'discount_name' => $void_d->discount_name,
                'discount_type' => $void_d->discount_type,
                'discount' => $void_d->discount,
                'total_discount' => $void_d->total_discount,
                'comment' => $void_d->comment,
                'without' => $void_d->without,
                'pick_up' => $void_d->pick_up,
                'user' => $void_d->user,
                'user_id' => $void_d->user_id,
                'branch_id' => $void_d->branch_id,
                'subgroup_id' => $void_d->subgroup_id,
                'subgroup_name' => $void_d->subgroup_name,
                'status' => $void_d->status,
                'date' => $void_d->date,
            ]);
            $del = Void_d::where('order_id',$void_d->order_id)->delete();
        }


        $shift       = Shift::limit(1)->where(['branch_id'=>$branch,'status'=>1])->update(['status'=>0]);
        $first_shift = Shift::where(['branch_id'=>$branch])->min('shiftid');
        $update      = Shift::where(['branch_id'=>$branch,'shiftid'=>$first_shift])->update(['status'=>1]);
        $this->CloseDay();
        if($shift && $first_shift){
            SerialShift::where(['branch'=>$branch])->delete();
            return response()->json([
                'status'=>'success',
                'msg'=>'Day Is Closed',
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'msg'=>'Day cannot be closed',
            ]);
        }
    }
    public function CheckLastOrder(){
        $branch = $this->GetBranch();
        $user = $this ->GetUser();
        $order = Orders_d::where(['branch_id'=>$branch,'user_id'=>$user,'hold_list'=>0])->max('order_id');
        $order_only = Orders_d::limit(1)->where(['branch_id'=>$branch,'user_id'=>$user,'order_id'=>$order])->select(['take_order','op','table'])->first();
        if(Wait_order::where(['branch_id'=>$branch,'order_id'=>$order])->count() == 0){
            Orders_d::where(['branch_id'=>$branch ,'order_id'=>$order])->delete();
            SerialCheck::where(['branch_id'=>$branch,'order'=>$order])->delete();
            DB::statement('ALTER TABLE serial_check AUTO_INCREMENT = '.(count(SerialCheck::all())+1).';');
        }else{
            $count_wait = Wait_order::where(['branch_id'=>$branch,'order_id'=>$order])->count();
            $count_wait_pick = Wait_order::where(['branch_id'=>$branch,'order_id'=>$order,'status_take'=>0])->count();
            if($count_wait == $count_wait_pick){
                Orders_d::where(['branch_id'=>$branch ,'order_id'=>$order])->delete();
                SerialCheck::where(['branch_id'=>$branch,'order'=>$order])->delete();
                $wait_del = Wait_order::where(['branch_id'=>$branch,'order_id'=>$order,'status_take'=>'0'])->get();
                foreach($wait_del as $del){
                    Wait_order::where(['id'=>$del->id])->delete();
                    Extra_wait_order::where(['number_of_order'=>$del->order_id,'wait_order_id'=>$del->id])->delete();
                    Details_Wait_Order::where(['number_of_order'=>$del->order_id,'wait_order_id'=>$del->id])->delete();
                }
                DB::statement('ALTER TABLE serial_check AUTO_INCREMENT = '.(count(SerialCheck::all())+1).';');
            }else{
                $wait_del = Wait_order::where(['branch_id'=>$branch,'order_id'=>$order,'status_take'=>'0'])->get();
                foreach($wait_del as $del){
                    Extra_wait_order::where(['number_of_order'=>$del->order_id,'wait_order_id'=>$del->id])->delete();
                    Details_Wait_Order::where(['number_of_order'=>$del->order_id,'wait_order_id'=>$del->id])->delete();
                }
                Wait_order::where(['branch_id'=>$branch,'order_id'=>$order,'status_take'=>0])->delete();
            }
        }
        if($order_only){
            if($order_only->take_order == 0){
                if($order_only->op == 'Table'){
                    Table::limit(1)->where(['branch_id'=>$branch,'number_table'=>$order_only->table])
                        ->update([
                            'state'=>0,
                            'user'=>0,
                            'user_id'=>0,
                            'follow'=>0,
                            'master'=>0,
                            'merged'=>0,
                            'guest'=>0,
                            'min_charge'=>0,
                        ]);
                }
            }
        }
    }
    public function CheckWaitFail(){
        $user = Auth::user()->id;
        $branch = Auth::user()->branch_id;
        if(Wait_order::where(['branch_id'=>$branch,'user_id'=>$user,'status_take'=>'0'])->count() > 0){
            $wait = Wait_order::where(['branch_id'=>$branch,'user_id'=>$user,'status_take'=>'0'])->get();
            foreach ($wait as $checkWait){
                if(Orders_d::limit(1)->where(['order_id'=>$checkWait->order_id,'hold_list'=>0])->count() != 0){
                    Extra_wait_order::where(['number_of_order'=>$checkWait->order_id,'wait_order_id'=>$checkWait->id])->delete();
                    Details_Wait_Order::where(['number_of_order'=>$checkWait->order_id,'wait_order_id'=>$checkWait->id])->delete();
                    $wait = Wait_order::where(['branch_id'=>$branch,'user_id'=>$user,'status_take'=>0])->delete();
                }
            }
        }
    }
    public function CheckPrintWait($order){
        $branch = $this->GetBranch();
        $orders = Orders_d::limit(1)->where(['branch_id'=>$branch,'order_id'=>$order])->select(['table'])->first();
        $tableopen = Table::limit(1)->where(['branch_id'=>$branch,'number_table'=>$orders->table])->select(['state'])->first();
        if($tableopen){
            if($tableopen->state == 2){
                $update_table = Table::where(['branch_id'=>$branch,'number_table'=>$orders->table])->update([
                    'state'=> 4,
                ]);
            }elseif($tableopen->state == 3){
                $update_table = Table::where(['branch_id'=>$branch,'number_table'=>$orders->table])->update([
                    'state'=> 5,
                ]);
            }
        }

    }
    public function SerialShift($order){
        $branch = $this->GetBranch();
        $shift  = $this->Shift();
        $serial = 0;
        $data = [];
        $data = array('branch'=>$branch,'shift'=>$shift,'order_id'=>$order,'serial_shift'=>$serial);

        if(SerialShift::where(['branch'=>$branch,'shift'=>$shift])->count() > 0){
            if(SerialShift::limit(1)->where(['branch'=>$branch,'shift'=>$shift,'order_id'=>$order])->count() == 0){
                $serial = SerialShift::where(['branch'=>$branch,'shift'=>$shift])->max('serial_shift');
                $serial++;
                $data['serial_shift'] = $serial;
                $add = SerialShift::create($data);
                $update = Orders_d::limit(1)->where(['branch_id'=>$branch,'order_id'=>$order])->update([
                    'serial_shift'=> $serial
                ]);
            }
        }else{
            $data['serial_shift'] = 1;
            $serial = 1;
            $add = SerialShift::create($data);
            $update = Orders_d::limit(1)->where(['branch_id'=>$branch,'order_id'=>$order])->update([
                'serial_shift'=> $serial
            ]);
        }
    }
    public function AddTotalOrder($tr,$order){
        $branch = $this->GetBranch();
        $total = 0;
        $discount = 0;
        $cash = 0;
        $data  = $this->calculate_taxandservice($tr,$order);
        $dis = Orders_d::limit(1)->where(['branch_id'=>$branch,'order_id'=>$order])->select(['discount','discount_type','cash','method','total'])->first();
        if($dis->discount_type == "Ratio"){
            $discount = ($dis->discount * $data[0]['total']) / 100;
        }else{
            $discount = $dis->discount;
        }

        $total = $data[0]['total'] + $data[0]['service'] + $data[0]['tax'] - $discount;
        if($dis->method == 'cash'){
            $cash = $total;
        }else{
            $cash = $dis->cash;
        }
        $update = Orders_d::limit(1)->where(['branch_id'=>$branch,'order_id'=>$order])->update([
            'total_discount'  => $discount,
            'sub_total'       => $data[0]['total'],
            'total'           => $total,
            'cash'           => $cash,
            'service_ratio'   => $data[0]['service_ratio'],
            'services'        => $data[0]['service'],
            'tax'             => $data[0]['tax'],
            'tax_ratio'       => $data[0]['tax_ratio']
        ]);
    }
    public function AddTotalWait($order){
        $Wait = Wait_order::where('order_id',$order)->select([
            'id',
            'total',
            'total_extra',
            'price_details',
            'total_discount',
        ])->get();
        foreach($Wait as $or){
            $up = Wait_order::limit(1)->where('id',$or->id)->update([
                'all_total'=>$or->total + $or->total_extra + $or->price_details - $or->total_discount,
            ]);
        }
    }
    public function ReportShift(){
        $date     = $this->CheckDayOpen();
        $branch   = $this->GetBranch();
        $shift    = $this->Shift();
        $getShift = Shift::where(['id'=>$shift])->first();
        $time     = $this->Get_Time();
        $shift_id = $getShift->shiftid;
        $shift_na = $getShift->shift;
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
        // Cal Order_No
        $data['order_no'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->count();
        // Cal Order_Min
        $data['min_order'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->min('order_id');
        // Cal Order_Max
        $data['max_order'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->max('order_id');
        // Cal gust_no
        $data['gust_no'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('gust');
        // Cal sub_total
        $data['sub_total'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('sub_total');
        // Cal cash
        $data['cash'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('cash');
        // Cal visa
        $data['visa'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('visa');
        // Cal hos
        $sud_hos = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'hos'=>1])->get();
        $total_new_hos = 0;
        foreach ($sud_hos as $orderHos){
            $wait = Wait_order::where(['order_id'=>$orderHos->order_id])->get();
            foreach ($wait as $w){
                $total_new_hos += $w->total + $w->price_details + $w->total_extra;
            }
        }
        $data['hos'] = $total_new_hos;
        // Cal total_cash
        $data['total_cash'] = $data['cash'] + $data['visa'] + $data['hos'];
        $data['gust_avarge'] = $data['cash'] / $data['gust_no'];

        // Cal total_cash
        $data['customer_payments'] = $data['cash'] + $data['visa'];

        // Calculate Toatal table
        $data['table'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'Table'])->sum('total');
        // Calculate Toatal Delivery
        $data['delivery'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'Delivery'])->sum('total');
        // Calculate Toatal TO_GO
        $data['to_go'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'TO_GO'])->sum('total');

        // Calculate Toatal table_service
        $data['table_ser'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'Table'])->sum('services');
        // Calculate Toatal Delivery_service
        $data['delivery_ser'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'Delivery'])->sum('services');
        // Calculate Toatal TO_GO_service
        $data['to_go_ser'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'TO_GO'])->sum('services');

        // Calculate Toatal table_TAX
        $data['table_tax'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'Table'])->sum('tax');
        // Calculate Toatal Delivery_TAX
        $data['delivery_tax'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'Delivery'])->sum('tax');
        // Calculate Toatal TO_GO_TAX
        $data['to_go_tax'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'TO_GO'])->sum('tax');


        // Calculate Toatal table_NO_ORDER
        $data['table_no'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'Table'])->count();
        // Calculate Toatal Delivery_NO_ORDER
        $data['delivery_no'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'Delivery'])->count();
        // Calculate Toatal TO_GO_NO_ORDER
        $data['to_go_no'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch,'op'=>'TO_GO'])->count();


        // Calculate Toatal tax
        $data['tax'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('tax');
        // Calculate Toatal service
        $data['service'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('services');

        // Calculate Toatal tip
        $data['tip'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('tip');
        // Calculate Toatal r_bank
        $data['r_bank'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('r_bank');

        // Calculate Toatal Extra
        $data['extras'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('total_extra');
        // Calculate Toatal Details
        $data['details'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('total_details');
        $all_orders = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->select(['order_id'])->get();
        foreach($all_orders as $order)
        {

            $wait = Wait_order::where(['order_id'=>$order->order_id])->select(['subgroup_id','all_total','quantity','total_discount'])->get();
            foreach($wait as $w){
                $discount_item += $w->total_discount;
                for($x = 0 ; $x < $groups->count() ; $x++){
                    if($groups[$x]['id'] == $w->subgroup_id){
                        $groups[$x]['quantity'] += $w->quantity;
                        $groups[$x]['total'] += $w->all_total;
                    }
                }
            }
        }

        // Calculate Toatal discount
        $data['discount'] = Orders_d::where(['shift_status'=>'1','state' => '0','branch_id'=>$branch])->sum('total_discount') + $discount_item;

        // Calculate The Pr
        $all_total_groups = $groups->sum('total');
//        for($x = 0 ; $x < $groups->count() ; $x++){
//            if($groups[$x]['id'] == $w->subgroup_id){
//                $groups[$x]['total_pre'] += ($all_total_groups * 100) / $groups[$x]['total'];
//            }
//        }
        $saveData = array('date'=>$date,'cash'=>$data['cash'],'visa'=>$data['visa'],'hos'=>$data['hos'],'total'=>$data['cash'] + $data['visa'],'shift'=>$shift,'user'=>Auth::user()->id);
        CloseShiftDaily::create($saveData);
        $insert_data = CloseShift::create($data);
        foreach($groups as $gr){
            CloseShiftGroup::create([
                'close_shift'=>$insert_data->id,
                'name'=>$gr->name,
                'total'=>$gr->total,
                'quantity'=>$gr->quantity,
                'total_pre'=>$gr->total_pre,
            ]);
        }
    }
    public function SendToFirstRep($order){
        $AddData = FIRST_REP::create([
            'date_of_order' =>$order['date'],
            't_order'       =>$order['time'],
            'name'          =>$order['item'],
            'quantity'      =>$order['quan'],
            'type'          =>$order['val_type'],
            'waiter_name'   =>$order['waiter'],
            'serial_shift'  =>$order['serial'],
            'op'            =>$order['op'],
            'table'         =>$order['table_id'],
        ]);
    }
    public function incId(){
        $id = Orders_d::count();
        return $id;
    }
    public function checkTable(){
        $branch = $this->GetBranch();
        $tables = Table::where(['branch_id'=>$branch , 'state'=>'1'])->select(['number_table'])->get();
        foreach ($tables as $table){
            if(Wait_order::where(['branch_id'=>$branch,'table_id'=>$table->number_table,'state'=>'1'])->count() == 0){
                $update_state = Table::where(['branch_id'=>$branch,'number_table'=>$table->number_table])
                    ->update([
                        'state'      =>0,
                        'user'       =>0,
                        'user_id'    =>0,
                        'table_open' =>0,
                    ]);
            }
        }
    }
    public function addActionTable($data){
        ActionTables::create($data);
    }
    public function removeActionTable(){
        $userTable  = Auth::user()->id;
        $branchUser = Auth::user()->branch_id;
        ActionTables::where(['user_id'=>$userTable,'branch'=>$branchUser])->delete();
    }
    public function checkTransfers(){
        $branch = Auth::user()->branch_id;
        $user = Auth::user()->id;
        $data = [];
        if(TransferUsers::limit(1)->where(['branch'=>$branch,'n_user'=>$user,'status'=>'Wait'])->count() > 0){
            $data = TransferUsers::with('CurrentUser')->where(['branch'=>$branch,'n_user'=>$user,'status'=>'Wait'])->select(['order','table','c_user'])->get();
        }else{
            $data = null;
        }
        return $data;
    }
    public function deleteTransfer($table){
        $branch = Auth::user()->branch_id;
        $data = TransferUsers::where(['branch'=>$branch,'table'=>$table])->delete();
    }
    public function LogInfo($data){
        $branch = $this->GetBranch();
        $user = Auth::user()->email;
        $order = 0;
        $table = 0;
        $type  = 0;
        $note  = 0;
        $item  = 0;
        if(isset($data['order'])){ $order = $data['order'];}
        if(isset($data['table'])){ $table = $data['table'];}
        if(isset($data['type'])){ $type = $data['type'];}
        if(isset($data['note'])){ $note = $data['note'];}
        if(isset($data['item'])){ $item = $data['item'];}
        LogInfo::create([
            'branch'=>$branch,
            'user'=>$user,
            'order'=>$order,
            'table'=>$table,
            'type'=>$type,
            'note'=>$note,
            'item'=>$item,
        ]);
    }
    public function checkTableStatus(){
        $getTables = Table::where(['branch_id'=>$this->GetBranch(),'state'=>0])->get();
        foreach ($getTables as $table){
            $orders = Orders_d::where(['branch_id'=>$this->GetBranch(),'table' => $table->number_table])->update(['state'=>0]);
        }
    }
    public function calcBeforeCloseShift(){
        $branch = $this->GetBranch();
        $orders = Orders_d::where(['state' => '0','branch_id'=>$branch])->get();
        foreach ($orders as $order){
            if(Wait_order::where(['order_id'=>$order->order_id])->count() == 0){
                    $order_test = Item::limit(1)->where(['name'=>'TEST'])->first();
                    $group = Group::limit(1)->where('branch_id',Auth::user()->branch_id)
                        ->where('id',$order_test->group_id)
                        ->first();
                    $time_now = $this->Get_Time();
                    $data_test = Wait_order::create(
                        [
                            'item_id'            => $order_test->id,
                            'name'               => $order_test->name,
                            'price'              => $order_test->price,
                            'order_id'           => $order->order_id,
                            'table_id'           => $order->table,
                            'status_take'        => 1,
                            'pick_up'            => 1,
                            'sub_num_order'      => 1,
                            'subgroup_id'        => $group->id,
                            'subgroup_name'      => $group->name,
                            'total'              => $order_test->price,
                            'quantity'           => 1,
                            'op'                 => $order->op,
                            'state'              => 0,
                            'user'               => Auth::user()->name,
                            'user_id'            => Auth::user()->id,
                            'branch_id'          => Auth::user()->branch_id,
                        ]
                    );
                    $update_or = Orders_d::limit(1)->where('order_id',$order->order_id)->update([
                        'state' => 0,
                        'method'=>'cash',
                        'no_print'=>1,
                        'devcashier'=>$order->dev_id,
                        't_closeorder'=>$time_now,
                    ]);
                    if($order->serial_shift == 0){
                        $this->SerialShift($order->order_id);
                    }
                }
            $this->AddTotalOrder($order->op,$order->order_id);
        }
    }

    public function orderLate(){
        $user = Auth::user();
        $devCounter = 0;
        $serialLate = [];
        $lSerial = 1;
        $devBranch = 0;
        $serial = SerialCheck::where(['branch_id'=>$user->branch_id])->select('branch_dev')->groupBy('branch_dev')->get();
        foreach ($serial as $row){
            $lSerial = 1;
            $devBranch = $row->branch_dev;
            $serialDev = SerialCheck::where(['branch_id'=>$user->branch_id,'branch_dev'=>$row->branch_dev])->orderBy('serial','asc')->get();

            foreach ($serialDev as $dev){
                if($serialDev->last()->id != $dev->id) {
                    if ($lSerial != $dev->serial) {
                        $serialLate[$devCounter]["order"]      = $row->branch_dev . $lSerial;
                        $serialLate[$devCounter]["serial"]     = $lSerial;
                        $serialLate[$devCounter]["branch_dev"] = $row->branch_dev;
                        $serialLate[$devCounter]["branch_id"]  = $dev->branch_id;
                        $devCounter++;
                    }
                }
                $lSerial = $dev->serial + 1;
            }
        }
        foreach ($serialLate as $key){
            if(SerialCheck::where(['order'=>$key['order']])->count() == 0) {
                $test = SerialCheck::create([
                    'order'=>$key['order'],
                    'serial'=>$key['serial'],
                    'branch_dev'=>$key['branch_dev'],
                    'branch_id'=>$key['branch_id'],
                ]);
            }
            if(Orders_m::where(['order_id'=>$key['order']])->count() == 0){
                if(Orders_m::where(['order_id'=>$key['order']-1])->count() > 0){
                    $order = Orders_m::where(['order_id'=>$key['order']-1])->first();
                }else{
                    $orderId = Orders_m::max('id');
                    $order = Orders_m::where(['order_id'=>$orderId])->first();
                }
                if($order) {
                    $lastserialshift = Orders_m::where(['branch_id' => $key['branch_id'], 'd_order' => $order->d_order])->max('serial_shift') + 1;
                    $order->id = Orders_m::max('id') + 1;
                    $order->serial_shift = $lastserialshift;
                    $order->discount_type = null;
                    $order->discount_name = null;
                    $order->total_discount = 0;
                    $order->order_id = $key['order'];
                    Orders_m::create([
                        'order_id' => $order->order_id,
                        'dev_id' => $order->dev_id,
                        'table' => $order->table,
                        'serial_shift' => $order->serial_shift,
                        'op' => $order->op,
                        'state' => $order->state,
                        'sub_total' => 0,
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
                        'discount' => null,
                        'discount_name' => null,
                        'discount_type' => null,
                        'total_discount' => 0,
                        'total_details' => 0,
                        'total_extra' => 0,
                        'total' => 0,
                        'shift' => $order->shift,
                        'cashier' => $order->cashier,
                        'services' => 0,
                        'service_ratio' => $order->service_ratio,
                        'state_service' => $order->state_service,
                        'tax' => 0,
                        'tax_ratio' => 0,
                        'state_tax' => 0,
                        'discount_tax_service' => $order->discount_tax_service,
                        'min_charge' => $order->min_charge,
                        'gust' => $order->gust,
                        'method' => $order->method,
                        'no_print' => $order->no_print,
                        'tip' => 0,
                        'cash' => 0,
                        'visa' => 0,
                        'hos' => 0,
                        'r_bank' => 0,
                        'devcashier' => $order->devcashier,
                        't_closeorder' => $order->t_closeorder,
                    ]);
                    $order_test = Item::limit(1)->where(['name' => 'TEST'])->first();
                    $group = Group::limit(1)->where('branch_id', Auth::user()->branch_id)
                        ->where('id', $order_test->group_id)
                        ->first();
                    $time_now = $this->Get_Time();
                    $data_test = Wait_order_m::create(
                        [
                            'item_id' => $order_test->id,
                            'name' => $order_test->name,
                            'price' => $order_test->price,
                            'order_id' => $key['order'],
                            'table_id' => $order->table,
                            'd_order' => $order->d_order,
                            'sub_num_order' => 1,
                            'subgroup_id' => $group->id,
                            'subgroup_name' => $group->name,
                            'total' => $order_test->price,
                            'quantity' => 1,
                            'op' => $order->op,
                            'state' => 0,
                            'user' => $order->user,
                            'user_id' => $order->user_id,
                            'branch_id' => $key['branch_id'],
                        ]
                    );
                }else{
                    return $key;
                }
            }
        }
    }

    public function deleteOrderRep(){
        $orders = Orders_m::with("WaitOrders","WaitOrders.Details",'WaitOrders.Extra')->where(['d_order'=>"2023-09-04"])->orderBy('order_id','asc')->get();
        $flag = 1;
        foreach ($orders as $row){
            foreach ($row->WaitOrders as $wait){
                    if ($wait->extra->count() > 0) {
                        $totalExtra = 0;

                        foreach ($wait->extra as $extra){
                            if($extra->price == 19 && $flag == 1){
                                $totalExtra += $extra->price * $wait->quantity - 19;
                                $flag = 0;
                            }else{
                                $totalExtra += $extra->price * $wait->quantity;

                            }
                        }
                        Wait_order_m::where(['id'=>$wait->id])->update([
                            'total_extra' => $totalExtra
                        ]);
                    }
            }

//            foreach ($row->WaitOrders as $wait){
//
//                if($wait->discount_name == null){
//                    if($wait->extra->count() > 0){
//                        Extra_wait_order_m::where(['wait_order_id'=>$wait->id])->delete();
//                    }
//                    if($wait->details->count() > 0){
//                        Details_Wait_Order::where(['wait_order_id'=>$wait->id])->delete();
//                    }
//                    Wait_order_m::where(['id'=>$wait->id])->delete();
//                }
//            }
//            Orders_m::where(['id'=>$row->id])->delete();
        }
    }
    public function OrderNotTake(){
      $orders = Orders_d::with("WaitOrders","WaitOrders.Details",'WaitOrders.Extra')->whereState(1)->whereSerialShift(0)->get();
      foreach($orders as $order){
          foreach($order->WaitOrders as $wait){
              if($order->status_take == 0){
                  if($wait->extra->count() > 0){
                      Extra_wait_order::where(['wait_order_id'=>$wait->id])->delete();
                  }
                  if($wait->details->count() > 0){
                      Details_Wait_Order::where(['wait_order_id'=>$wait->id])->delete();
                  }
                  Wait_order::where(['id'=>$wait->id])->delete();
                  Orders_d::where(['id'=>$wait->id])->delete();
              }
          }
          if($order->WaitOrders->count() == 0){
              $order_test = Item::limit(1)->where(['name' => 'TEST'])->first();
              $group = Group::limit(1)->where('branch_id', Auth::user()->branch_id)
                  ->where('id', $order_test->group_id)
                  ->first();
              $data_test = Wait_order::create(
                  [
                      'item_id' => $order_test->id,
                      'name' => $order_test->name,
                      'price' => $order_test->price,
                      'order_id' => $order->order_id,
                      'table_id' => $order->table,
                      'sub_num_order' => 1,
                      'subgroup_id' => $group->id,
                      'subgroup_name' => $group->name,
                      'total' => $order_test->price,
                      'quantity' => 1,
                      'op' => $order->op,
                      'state' => 0,
                      'user' => $order->user,
                      'user_id' => $order->user_id,
                      'branch_id' => $order->branch_id,
                  ]
              );
          }
          $this->SerialShift($order->order_id);
          $order->state = 0;
          $order->devcashier = $order->dev_id;
          $order->t_closeorder = $this->Get_Time();
          $order->method = "cash";
          $order->save();
          $this->AddTotalOrder($order->op,$order->order_id);
      }
    }

    public function reCalcOrder($orderId){
        $order = Orders_d::with("WaitOrders","WaitOrders.Details",'WaitOrders.Extra')->whereOrderId($orderId)->first();
        foreach($order->WaitOrders as $wait){
            $extra = 0;
            $details = 0;
            $discount = 0;
            $allTotal = 0;
            if($wait->extra->count() > 0){
                $extra = $wait->extra->sum('price') * $wait->quantity ;
            }
            if($wait->details->count() > 0){
                $details = $wait->details->sum('price') * $wait->quantity ;
            }
            $wait->total = $wait->quantity * $wait->price;
            $wait->total_extra = $extra;
            $wait->price_details = $details;
            $allTotal = $wait->total + $extra + $details;
            if($wait->discount != 0 || $wait->discount_type != null){
                if($wait->discount_type == "Ratio"){
                    $wait->total_discount = $allTotal * $wait->discount / 100;
                }
            }
            $wait->save();
        }
        $this->AddTotalOrder($order->op,$order->order_id);
    }
}

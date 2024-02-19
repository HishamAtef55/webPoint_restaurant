<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Device;
use App\Models\Extra_wait_order;
use App\Models\Details_Wait_Order;
use App\Models\Orders_d;
use App\Models\Orders_m;
use App\Models\Service_tables;
use App\Models\SerialCheck;
use App\Models\SerialShift;
use App\Models\Table;
use App\Models\Delivery;
use App\Models\ToGo;
use App\Models\Delavery;
use App\Models\Wait_order;
use App\Models\OrdersM;
use App\Models\LogTransfer;
use App\Models\Wait_order_m;
use Illuminate\Http\Request;
use App\Traits\All_Functions;
use App\Traits\All_Notifications_menu;

use Illuminate\Support\Facades\Auth;

class MovetoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Functions;
    use All_Notifications_menu;

    public function moveto()
    {
        $this->CheckLastOrder();
        $this->CheckWaitFail();
        $this->removeActionTable();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        $master_tables = [];
        $tables        = [];

        $user = Auth::user();
        if($user->can('open-tables')){
            $master_tables = Table::where(['branch_id'=>$user->branch_id])
                ->where('state','>=',1)->select(['number_table'])->get();
            $tables = Table::where('branch_id',$user->branch_id)->select(['number_table'])->get();
        }else{
            $master_tables = Table::where(['branch_id'=>$user->branch_id])
                ->where('user_id',$user->id)->select(['number_table'])->get();
            $tables = Table::where(['branch_id'=>$user->branch_id,'user_id'=>0])->orWhere(['user_id'=>$user->id])->select(['number_table'])->get();
        }

        return view('menu.moveto',compact
        ([
            'to_noti_hold',
            'master_tables',
            'tables',
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
        ]));
    }
    // get items in Main table
    public function search_main_table(Request $request)
    {
        $branch = Auth::user()->branch_id;
        $allOrders = Wait_order::with(['Extra','Details'])
            ->where(['branch_id'=>$branch,'table_id'=>$request->ID,'state'=>1])
            ->get();
        return response()->json($allOrders);
    }
    // get items in new table
    public function search_new_table(Request $request)
    {
        $branch = Auth::user()->branch_id;
        $allOrders = Wait_order::with(['Extra','Details'])
            ->where(['branch_id'=>$branch,'table_id'=>$request->ID , 'state'=>1])
            ->get();
        return response()->json($allOrders);
    }
    public function moveto_item(Request $request)
    {
        $old_total = 0;
        $new_total = 0;
        $branch_main = $this->GetBranch();
        if(!isset($request->Data)){
            return ['status'=>false,'msg'=>'Not Data Found'];
        }else{
            $order_in = Orders_d::limit(1)->where(['branch_id'=>$branch_main,'table'=>$request->maintable,'state'=>1])->select(['order_id','user','user_id','op','d_order'])->first();
            $count_wait = Wait_order::where(['order_id'=>$order_in->order_id])->count();
            if($count_wait == $request->size_data && $request->rightLength == 0){
                $order_in_to = Orders_d::limit(1)->where(['branch_id'=>$branch_main,'table'=>$request->table_id,'state'=>1])->select(['order_id','user','user_id','op','d_order'])->first();
                if(Orders_d::limit(1)->where(['branch_id'=>$branch_main,'table'=>$request->table_id,'state'=>1])->count() > 0){
                    $loginfo = array(
                        'type' => 'move to order open table',
                        'table'=>  $request->table_id,
                        'note' => 'move to order from table number ' . $request->maintable ." to table " . $request->table_id,
                        'order'=> $order_in_to->order_id,
                        'op'   => $order_in_to->op,
                        'time' => $this->Get_Time(),
                        'date' => $order_in_to->d_order,
                      );
                    $this->LogInfo($loginfo);
                    foreach(Wait_order::where(['order_id'=>$order_in->order_id])->get() as $row){
                        $loginfo = array(
                            'type' => 'move to',
                            'table'=>  $request->table_id,
                            'note' => 'move to item from table number ' . $request->maintable ." to table " . $request->table_id,
                            'order'=> $order_in_to->order_id,
                            'op'   => $order_in_to->op,
                            'time' => $this->Get_Time(),
                            'date' => $order_in_to->d_order,
                            'qty'  => $row->quantity,
                            'item' => $row->name,
                            'item_id'=> $row->item_id,
                          );
                        $this->LogInfo($loginfo);
                    }
                    $wait_in_all     = Wait_order::where(['order_id'=>$order_in->order_id])->update(['table_id'=>$request->table_id,'order_id'=>$order_in_to->order_id]);
                    $wait_in_all_ex  = Extra_wait_order::where(['number_of_order'=>$order_in->order_id])->update(['number_of_order'=>$order_in_to->order_id]);
                    $wait_in_all_de  = Details_Wait_Order::where(['number_of_order'=>$order_in->order_id])->update(['number_of_order'=>$order_in_to->order_id]);
                    $del_in_order    = Orders_d::limit(1)->where(['branch_id'=>$branch_main,'order_id'=>$order_in->order_id,'state'=>1])->delete();
                    $del_serialcheck = SerialCheck::where(['order'=>$order_in->order_id])->delete();
                    $del_serialsheft = SerialShift::where(['order_id'=>$order_in->order_id])->delete();
                    $this->AddTotalOrder('Table',$order_in_to->order_id);

                }else{
                    $loginfo = array(
                        'type' => 'move to order closed table',
                        'table'=>  $request->table_id,
                        'note' => 'move to order from table number ' . $request->maintable ." to table " . $request->table_id,
                        'order'=> $order_in->order_id,
                        'op'   => $order_in->op,
                        'time' => $this->Get_Time(),
                        'date' => $order_in->d_order,
                      );
                    $this->LogInfo($loginfo);
                    foreach(Wait_order::where(['order_id'=>$order_in->order_id])->get() as $row){
                        $loginfo = array(
                            'type' => 'move to',
                            'table'=>  $request->table_id,
                            'note' => 'move to item from table number ' . $request->maintable ." to table " . $request->table_id,
                            'order'=> $order_in->order_id,
                            'op'   => $order_in->op,
                            'time' => $this->Get_Time(),
                            'date' => $order_in->d_order,
                            'qty'  => $row->quantity,
                            'item' => $row->name,
                            'item_id'=> $row->item_id,
                          );
                        $this->LogInfo($loginfo);
                    }
                    $order_in_all = Orders_d::limit(1)->where(['branch_id'=>$branch_main,'table'=>$request->maintable,'state'=>1])->update(['table'=>$request->table_id]);
                    $wait_in_all = Wait_order::where(['order_id'=>$order_in->order_id])->update(['table_id'=>$request->table_id]);
                    $update_state = \App\Models\Table::where(['branch_id'=>$branch_main,'number_table'=>$request->table_id])
                        ->update([
                            'state'      =>1,
                            'user'       =>$order_in->user,
                            'user_id'    =>$order_in->user_id,
                            'table_open' =>1,
                        ]);
                    $this->AddTotalOrder('Table',$order_in->order_id);
                }

                $update_state = \App\Models\Table::where(['branch_id'=>$branch_main,'number_table'=>$request->maintable])
                    ->update([
                        'state'      =>0,
                        'user'       =>0,
                        'user_id'    =>0,
                        'table_open' =>0,
                    ]);
                return  response()->json(['status'=>'true']);
            }else {
                $newID = 0;
                $order = '';
                $serial = 0;
                $order_last_w = 0;
                $order_cal = 0;
                $check_order_test = 0;
                $shift = $this->Shift();
                if (Orders_d::limit(1)->where(['table' => $request->table_id, 'state' => 1])->count() == 0) {
                    $serial = $this->get_new_serial($branch_main , $order , $request->Order_Number_dev);
                    $check_order_test = 1;
                }else{
                    $order_h = Orders_d::limit(1)->where(['table' => $request->table_id, 'state' => 1])->select(['order_id'])->first();
                    $serial = $order_h->order_id;
                    $check_order_test = 0;
                }
                date_default_timezone_set('Africa/Cairo');
                $time_now = date(' H:i');
                $day_now = $this->CheckDayOpen();
                // check Order
                foreach($request->Data as $data)
                {
                    $Sub_no_Order = $this->Increase_Sub_Order($serial ,$branch_main);
                    $find_row = Wait_order::with('Extra','details')
                        ->where('id',$data['idRow'])
                        ->get();
                    if($order_last_w == 0){
                        $order_last_w = $find_row[0]->order_id;
                    }
                    $this->open_table($request->table_id);
                    $check_2_item = Wait_order::where('branch_id',$branch_main)->where('table_id',$request->table_id)
                        ->where('item_id', $find_row[0]->item_id)
                        ->where('moved',1)
                        ->select(['id','quantity','discount'])
                        ->get();
                    $new_total =  $find_row[0]->price * $data['quantity'];

                    $new_record = Wait_order :: create([
                        'item_id'            => $find_row[0]->item_id,
                        'table_id'           => $request->table_id,
                        'name'               => $find_row[0]->name,
                        'price'              => $find_row[0]->price,
                        'comment'            => $find_row[0]->comment,
                        'without'            => $find_row[0]->without,
                        'total'              => $find_row[0]->price * $data['quantity'],
                        'discount'           => $find_row[0]->discount,
                        'discount_name'      => $find_row[0]->discount_name,
                        'discount_type'      => $find_row[0]->discount_type,
                        'total_discount'     => $find_row[0]->total_discount,
                        'price_details'      => $find_row[0]->price_details,
                        'total_extra'        => $find_row[0]->total_extra,
                        'all_total'          =>$find_row[0]->price * $data['quantity'],
                        'order_id'           => $serial,
                        'sub_num_order'      => $Sub_no_Order,
                        'quantity'           => $data['quantity'],
                        'subgroup_id'        => $data['group_id'],
                        'subgroup_name'      => $data['group_name'],
                        'moved'              => 1,
                        'user'               => Auth::user()->name,
                        'user_id'            => Auth::user()->id,
                        'branch_id'          => Auth::user()->branch_id,
                        'op'                 =>"Table",
                        'state'              =>1,
                        'status_take'        =>1,
                        'pick_up'=>1
                    ]);

                    
                    $newID = $new_record->id;
                    // Copy same Extra in old order to new order
                    foreach($find_row[0]->extra as $extra)
                    {
                        $new_extra = Extra_wait_order::create([
                            'number_of_order' => $serial,
                            'extra_id'        => $extra->extra_id,
                            'price'           => $extra->price,
                            'name'            => $extra->name,
                            'wait_order_id'   => $newID
                        ]);
                        $del_extra = Extra_wait_order::where(['number_of_order'=>$extra->number_of_order , 'wait_order_id'=>$extra->wait_order_id])->delete();
                    }
                    //Copy same details in old order to new order
                    foreach($find_row[0]->details as $details)
                    {
                        $insert_Details = Details_Wait_Order::create([
                            'number_of_order'  => $serial,
                            'detail_id'        => $details->detail_id,
                            'price'            => $details->price,
                            'name'             => $details->name,
                            'wait_order_id'    => $newID,
                        ]);
                        $del_details = Details_Wait_Order::where(['number_of_order'=>$details->number_of_order , 'wait_order_id'=>$details->wait_order_id])->delete();
                    }

                    // Update Discount
                    $Quantity_old_item    = ($find_row[0]-> quantity) - ($data['quantity']);
                    if($Quantity_old_item == 0)
                    {
                        $delet_item = Wait_order::where('id',$data['idRow'])
                            ->delete();
                    }
                    // variable Discount
                    $type                 = $find_row[0]->discount_type ;
                    $discount             = $find_row[0]->discount ;
                    $disvalin             = 0;
                    $new_total_dis        = 0;
                    $old_total_dis        = 0;
                    $disvalinnew          = 0;
                    if($type  == "Ratio")
                    {
                        $math          = (($discount / 100) * $find_row[0]->price);
                        $new_total_dis = $math * $data['quantity'];
                        $old_total_dis = $math * $Quantity_old_item ;
                        $disvalin      = $find_row[0]->discount;
                        $disvalinnew   = $find_row[0]->discount;
                    }
                    elseif($type  == "Value")
                    {
                        $DisQuan = $discount / $find_row[0]-> quantity;
                        $new_total_dis = $DisQuan * $data['quantity'];
                        $old_total_dis = $DisQuan * $Quantity_old_item;
                        $disvalin      = $old_total_dis;
                        $disvalinnew   = $new_total_dis;
                    }

                    //Update Details
                    $new_details = 0;
                    $old_details = 0;

                    $one_details = $find_row[0]->price_details / $find_row[0]->quantity;
                    $new_details = $one_details * $data['quantity'] ;
                    $old_details = $one_details * ($find_row[0]->quantity - $data['quantity']);



                    //Update Extra
                    $new_extra = 0;
                    $old_extra = 0;
                    if($find_row[0]->total_extra)
                    {
                        $one_extra = $find_row[0]->total_extra / $find_row[0]->quantity;
                        $new_extra = $one_extra * $data['quantity'] ;
                        $old_extra = $one_extra * ($find_row[0]->quantity - $data['quantity']);
                    }

                    // Update table wait_order in mastre Order
                    if($Quantity_old_item > 0)
                    {
                        $old_wait = Wait_order::where('id',$find_row[0]->id)
                            ->update([
                                'quantity'       => $Quantity_old_item,
                                'total_extra'    => $old_extra,
                                'price_details'  => $old_details,
                                'total'          => $Quantity_old_item * $find_row[0]->price,
                                'total_discount' => round($old_total_dis , 2),
                                'discount'       => round($disvalin , 2),
                                'all_total'      => ($Quantity_old_item * $find_row[0]->price) + $old_extra + $old_details - $old_total_dis,
                            ]);
                    }
                    // Update table wait_order in slave Order
                    $new_wait = Wait_order::where('id',$newID)->update([
                        'total_extra'    => $new_extra,
                        'total_discount' => round($new_details,2),
                        'discount'       => round($disvalinnew , 2),
                        'price_details'  => $new_details,
                        'all_total'      =>$new_total + $new_extra + $new_details - $new_details
                    ]);
                    $waitTrans = Wait_order::limit(1)->with(['Without_m','Extra','Details'])->where('id',$newID)->first();
                    $loginfo = array(
                        'type'      => 'move to item',
                        'table'     => $request->table_id,
                        'note'      => 'move to item from table number ' . $request->maintable ." to table " . $request->table_id,
                        'order'      => $serial,
                        'item'       => $find_row[0]->name,
                        'item_id'    => $find_row[0]->item_id,
                        'op'         => "Table",
                        'time'       => $this->Get_Time(),
                        'date'       => $this->CheckDayOpen(),
                        'qty'        => $data['quantity'],
                        'Without'    => json_encode($waitTrans->without_m),
                        'extra'      => json_encode($waitTrans->extra),
                        'details'    => json_encode($waitTrans->details),
                      );
                    $this->LogInfo($loginfo);
                }
                if($check_order_test == 1)
                {
                    $state_table = Table::where('branch_id',$branch_main )->where('number_table',$request->table_id)->limit(1)->select(['state','min_charge','guest'])->first();
                    $min_charge = $state_table->min_charge * $state_table->guest ;

                    $check = Service_tables::limit(1)
                        ->where('branch',$branch_main)
                        ->select(['discount_tax_service','tax','service_ratio'])
                        ->first();

                    $discount_tax_service = $check->discount_tax_service;
                    $tax                  = $check->tax;
                    $service_ratio        = $check->service_ratio;
                    $idCount = $this->incId() + 1;

                    $new_order = Orders_d::create([
                        'order_id'        => $serial,
                        'dev_id'          => $request->Order_Number_dev,
                        'table'           => $request->table_id,
                        'op'              => 'Table',
                        'user'            => Auth::user()->name,
                        'user_id'         => Auth::user()->id,
                        'branch_id'       => Auth::user()->branch_id,
                        't_order'         => $time_now,
                        'd_order'         => $day_now,
                        'take_order'      =>1,
                        'state'           => 1,
                        'shift'           =>$shift,
                        'min_charge'      =>$min_charge,
                        'tax_ratio'       => $tax,
                        'service_ratio'   => $service_ratio,
                        'discount_tax_service' =>$discount_tax_service,
                    ]);
                    $this->SerialShift($serial);
                }

                $get_id_order = Orders_d::limit(1)->where(['branch_id'=>$branch_main,'table'=>$request->maintable , 'state'=>1])->select(['order_id'])->first();
                $get_id_order_to = Orders_d::limit(1)->where(['branch_id'=>$branch_main,'table'=>$request->table_id , 'state'=>1])->select(['order_id'])->first();
                $date = $this->Get_Date();
                $time = $this->Get_Time();
                
                $savelog = LogTransfer::create([
                    'branch'   =>$branch_main,
                    'date'     =>$date,
                    'time'     =>$time,
                    'from'     =>$request->maintable,
                    'to'       =>$request->table_id,
                    'waiter'   =>Auth::user()->name,
                    'type'     =>'Move-To',
                ]);
                if(isset($serial)){
                    $this->AddTotalOrder('Table',$serial);
                }
                if(isset($order_last_w)){
                    $this->AddTotalOrder('Table',$order_last_w);
                }
                if(isset($get_id_order_to->order_id)){
                    $this->AddTotalOrder('Table',$get_id_order_to->order_id);
                }
                return  response()->json(['status'=>'true']);
            }
        }
        //check table open or closed

    }
    /* ############################### Copy Check ############################### */
    public function copy_check(){

        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold     = $this->TOGO_hold();


        $tables = Table::where('branch_id',Auth::user()->branch_id)->get();
        return view('menu.copycheck',compact
        ([
            'to_noti_hold',
            'tables',
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
        ]));
    }
    public function view_check(Request $request){
        $orders = [];
        if($request->serial != null || $request->serial != ''){
            $orders = Orders_d::limit(1)->with(['Shift','Cashier','WaitOrders','WaitOrders.Extra','WaitOrders.Details'])->where('serial_shift',$request->serial)->first();
        }else{
            $orders = Orders_d::limit(1)->with(['Shift','Cashier','WaitOrders','WaitOrders.Extra','WaitOrders.Details'])->where('order_id',$request->order)->first();
            if(Orders_d::limit(1)->with(['Shift','Cashier'])->where('order_id',$request->order)->count() == 0){
                $orders = Orders_m::limit(1)->with(['Shift','Cashier','WaitOrders','WaitOrders.Extra','WaitOrders.Details'])->where('order_id',$request->order)->first();
            }
        }
        return response()->json([
            'orders'     =>$orders,
        ]);
    }
    public function print_copy_check(Request $request){

        $orders = [];
        if($request->serial != null || $request->serial != ''){
            $orders = Orders_d::limit(1)->where('serial_shift',$request->serial)->select(['op','order_id'])->first();
            $request->order = $orders->order_id;
            $item = 0;
        }else{
            $orders = Orders_d::limit(1)->where('order_id',$request->order)->select(['op'])->first();
            $item = 0;
            if(Orders_d::limit(1)->where('order_id',$request->order)->count() == 0){
                $orders = Orders_m::limit(1)->where('order_id',$request->order)->select(['op'])->first();
                $item = 1;
            }
        }


        $branch = $this->GetBranch();
        $type_check = 0;
        $no_copies = 0;
        $printer  = null;
        $device_print = Device::limit(1)->where(['branch_id'=>$branch,'id_device'=>$request->devId])->first();
        $printer = $device_print ->printer_invoice;
        switch($orders->op){
            case 'Table':{
                $type_check = 3;
                $ex = Service_tables::limit(1)->where(['branch'=>$branch])->select(['printers_input','invoic_copies'])->first();
                $no_copies = $ex ->invoic_copies;
            }break;
            case 'Delivery':{
                $type_check = 4;
                $ex = Delavery::limit(1)->where(['branch'=>$branch])->select(['printer','pilot_copies'])->first();
                $no_copies = $ex ->pilot_copies;
            }break;
            case 'TO_GO':{
                $type_check = 3;
                $ex = ToGo::limit(1)->where(['branch'=>$branch])->select(['printer','invoice_copies'])->first();
                $no_copies = $ex ->invoice_copies;
            }break;
        }
        $order_print = array(
            'branch'=>$branch,
            'order_id'=>$request->order,
            'type'=>$type_check,
            'no_copies'=>$no_copies,
            'printer'=>$printer,
            'val_type'=>'Copy',
            'item'=>$item,
        );
        $this->OrderPrint($order_print);
    }
}

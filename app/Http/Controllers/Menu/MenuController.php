<?php

namespace App\Http\Controllers\Menu;
use App\Http\Controllers\Controller;
use App\Models\Details_Wait_Order;
use App\Models\Device;
use App\Models\BarcodeItems;
use App\Models\extra;
use App\Models\Extra_wait_order;
use App\Models\Group;
use App\Models\Item;
use App\Models\ItemPrinters;
use App\Models\menu;
use App\Models\Others;
use App\Models\Printers;
use App\Models\ToGo;
use App\Models\Service_tables;
use App\Models\Delavery;
use App\Models\Orders_d;
use App\Models\Hole;
use App\Models\ComponentsItems;
use App\Models\Sub_group;
use App\Models\SerialCheck;
use App\Models\SerialShift;
use App\Models\Table;
use App\Models\Void_d;
use App\Models\Wait_order;
use App\Traits\All_Notifications_menu;
use App\Models\User;
use App\Models\Units;
use App\Models\WithoutMaterialsD;
use App\Models\material;
use Illuminate\Http\Request;
use App\Traits\All_Functions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Notifications_menu;
    use All_Functions;
    public function view_menu()
    {
        $this->CheckLastOrder();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti           = $this->TOGO();
        $to_noti_order     = $this->Togo_Order();
        $to_noti_hold      = $this->TOGO_hold();
        $subgroup = Group::where('branch_id',Auth::user()->branch_id)->get();
        return view('menu.menu',compact
        ([
            'subgroup',
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold'
        ]));
    }
    public function getnewsub(Request $request){
        $data = Sub_group::where('branch_id',Auth::user()->branch_id)->where('group_id',$request ->group)->where('active','Show')->orWhere('active',null)-> get();
        return response() ->json($data);

    }
    public function view_table()
    {
        $this->checkTable();
        $this->CheckDay();
        $this->CheckLastOrder();
        $this->CheckWaitFail();
        $this->removeActionTable();
        $this->checkTableStatus();
        $transfers = $this->checkTransfers();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $to_noti_hold      = $this->TOGO_hold();
        $printers = Printers::where(['active'=>'1'])->get();

        $user = User::where('branch_id',1)->get()->first();
        $holes = Hole::where('branch_id',1)->get();
        return view('menu.tables',compact
        ([
            'printers',
            'to_noti_hold',
            'user',
            'holes',
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
            'transfers'
        ]));
    }
    ############################ Export Item by Sub Group ##############################
    public function import_items(Request $request)
    {
        $items = Item::with(['Barcode','Details'])-> where('branch_id',Auth::user()->branch_id)->where(['sub_group_id'=>$request->ID,'active'=>1])->get();
        return response() ->json($items);
    }
    ################################## Save Items Wait OrdersM ############################
    protected function addOpenItem($name,$price,$group,$subgroup,$menu){
        $branch = $this->GetBranch();
        $data = Item::create
        ([
            'name' => $name,
            'note' => 'this is Open Item',
            'chick_name' => $name,
            'slep_name' => $name,
            'price' => $price,
            'takeaway_price' => $price,
            'dellvery_price' => $price,
            'cost_price' => $price,
            'time_during' => 0,
            'wight' => 0,
            'unit' => 0,
            'image' => 'not_found.jpg',
            'branch_id' => $branch,
            'menu_id' => $menu,
            'group_id' => $group,
            'sub_group_id' => $subgroup,
            'calories' => 0,
            'active' => 0,
        ]);
        return $data->id;
    }
    public function wait_items(Request $request)
    {
        if(isset($request->statusItem)){
            $branch = $this->GetBranch();
            $menu_master = Group::limit(1)->where(['id'=>$request->subgroup_id])->select(['menu_id'])->first();
            $menu = $menu_master->menu_id;
            $sub_master = Sub_group::limit(1)->where(['group_id'=>$request->subgroup_id])->select(['id'])->first();
            $group = $request->subgroup_id;
            $subgroup = $sub_master->id;
            $price = $request->Item_Price;
            $name = $request->Item_Name;
            $request->Item_ID = $this->addOpenItem($name,$price,$group,$subgroup,$menu);
            if (isset($request->printer)){
                foreach ($request->printer as $printer) {
                    $code = ItemPrinters::create([
                        'item_id'   => $request->Item_ID,
                        'printer'   => $printer,
                        'branch_id' => $branch,
                    ]);
                }
            }
        }
        $loginfo = array(
            'type' => 'wait item',
            'table'=>$request->Table_ID,
            'order'=>$request->Order_Number,
            'item' =>$request->Item_Name
        );
        $this->LogInfo($loginfo);
        $branch_main = Auth::user()->branch_id;
      // get New Order
      $new_serial = 0;
      date_default_timezone_set('Africa/Cairo');
      $time_now = date(' H-i');
      $day_now = $this->CheckDayOpen();
      $new_serial = $this->get_new_serial($branch_main,$request->Order_Number,$request->Order_Number_dev);
      //Increase Sub Order
      $new_order = $this->Increase_Sub_Order($new_serial ,$branch_main);
      $shift = $this->Shift();
      $idCount = $this->incId() + 1;
        switch ($request->operation)
        {
            case "TO_GO":
            {
                 // Generate New Device Order
                if(Orders_d::where('branch_id',$branch_main)->where('order_id',$new_serial)->limit(1)->count() > 0)
                {
                    //
                }else{
                    $check = ToGo::limit(1)
                    ->where('branch',$branch_main)
                    ->select(['discount_tax_service','tax','service_ratio'])
                    ->first();
                    $discount_tax_service = $check->discount_tax_service;
                    $tax                  = $check->tax;
                    $service_ratio        = $check->service_ratio;
                    $save_order = Orders_d::create([
                        'order_id'        => $new_serial,
                        'dev_id'          => $request->Order_Number_dev,
                        'table'           => '#'. $request->togo_table,
                        'op'              => $request->operation,
                        'user'            => Auth::user()->name,
                        'user_id'         => Auth::user()->id,
                        'branch_id'       => Auth::user()->branch_id,
                        't_order'         => $time_now,
                        'd_order'         => $day_now,
                        'state'           => 1,
                        'delivery_order' => 1,
                        'shift'           => $shift,
                        'tax_ratio'       => $tax,
                        'service_ratio'   => $service_ratio,
                        'gust'            =>1,
                        'discount_tax_service' =>$discount_tax_service,
                        'hold_list' =>0,
                        'total_details'=>0,
                        'total_extra'   =>0,
                    ]);
                }
                $Quantity = 0;
                if(empty($request->Quantity))
                {
                    $Quantity = 1;
                }else{
                    $Quantity = $request->Quantity;
                }
                $group = Group::limit(1)->where('branch_id',Auth::user()->branch_id)
                    ->where('id',$request->subgroup_id)
                    ->get()
                    ->first();
                $data = Wait_order::create(
                    [
                        'item_id'            => $request->Item_ID,
                        'name'               => $request->Item_Name,
                        'price'              => $request->Item_Price,
                        'order_id'           => $new_serial,
                        'sub_num_order'      => $new_order,
                        'subgroup_id'        => $group->id,
                        'subgroup_name'      => $group->name,
                        'total'              => $request->Item_Price * $Quantity,
                        'quantity'           => $Quantity,
                        'op'                 => $request->operation,
                        'state'              => 1,
                        'user'               => Auth::user()->name,
                        'user_id'            => Auth::user()->id,
                        'branch_id'          => Auth::user()->branch_id,
                    ]
                );
                return response() ->json(['order'=>$new_serial,'item_id'=>$request->Item_ID]);
            }break;
            case"Delivery":
            {
              if(Orders_d::where('branch_id',$branch_main)->where('order_id',$new_serial)->limit(1)->count() > 0)
              {
                  //
              }else{
                $check = Delavery::limit(1)
                    ->where('branch',$branch_main)
                    ->select(['discount_tax_service','tax','ser_ratio'])
                    ->first();
                $discount_tax_service = $check->discount_tax_service;
                $tax                  = $check->tax;
                $service_ratio        = $check->ser_ratio;
                  $save_order = Orders_d::create([
                      'id'              =>$idCount,
                      'order_id'        => $new_serial,
                      'dev_id'          => $request->Order_Number_dev,
                      'table'           => 0,
                      'op'              => $request->operation,
                      'user'            => Auth::user()->name,
                      'user_id'         => Auth::user()->id,
                      'branch_id'       => Auth::user()->branch_id,
                      't_order'         => $time_now,
                      'd_order'         => $day_now,
                      'state'           => 1,
                      'shift'           =>$shift,
                      'tax_ratio'       => $tax,
                      'service_ratio'   => $service_ratio,
                      'discount_tax_service' =>$discount_tax_service,
                      'hold_list'        =>0,
                       'gust'            =>1,

                  ]);
                }
                    $Quantity = 0;
                    if(empty($request->Quantity))
                    {
                        $Quantity = 1;
                    }else{
                        $Quantity = $request->Quantity;
                    }
                    $group = Group::limit(1)->where('branch_id',Auth::user()->branch_id)
                        ->where('id',$request->subgroup_id)
                        ->get()
                        ->first();
                    $data = Wait_order::create(
                        [
                          'item_id'            => $request->Item_ID,
                          'name'               => $request->Item_Name,
                          'price'              => $request->Item_Price,
                          'order_id'           => $new_serial,
                          'sub_num_order'      => $new_order,
                          'subgroup_id'        => $group->id,
                          'subgroup_name'      => $group->name,
                          'total'              => $request->Item_Price * $Quantity,
                          'quantity'           => $Quantity,
                          'op'                 => $request->operation,
                          'state'              => 1,
                          'user'               => Auth::user()->name,
                          'user_id'            => Auth::user()->id,
                          'branch_id'          => Auth::user()->branch_id,
                        ]
                    );
                return response() ->json(['order'=>$new_serial,'item_id'=>$request->Item_ID]);
            }break;
            default:
            {
              if(Orders_d::where('branch_id',$branch_main)->where('order_id',$new_serial)->limit(1)->count() > 0)
              {
                  //
              }else{
                // $print_table = 0;
                // $check_other = Table::limit(1)->where('branch_id',$branch_main )->where('number_table',$request->Table_ID)->select('hole')->first();
                // $hole_name   = Hole::limit(1)->where(['branch_id'=>$branch_main,'number_holes'=>$check_other->hole])->select(['name'])->first();
                // if($hole_name->name == 'Other'){
                //     $print_table = 1;
                // }
                $state_table = Table::where('branch_id',$branch_main )->where('number_table',$request->Table_ID)->limit(1)->select(['state','min_charge','guest'])->first();
                $min_charge = $state_table->min_charge * $state_table->guest ;

                $check = Service_tables::limit(1)
                    ->where('branch',$branch_main)
                    ->select(['discount_tax_service','tax','service_ratio'])
                    ->first();

                $discount_tax_service = $check->discount_tax_service;
                $tax                  = $check->tax;
                $service_ratio        = $check->service_ratio;

                $check_other = Table::limit(1)->where('branch_id',$branch_main )->where('number_table',$request->Table_ID)->select('hole')->first();
                $save_order = Orders_d::create([
                    'order_id'        => $new_serial,
                    'dev_id'          => $request->Order_Number_dev,
                    'table'           => $request->Table_ID,
                    'op'              => $request->operation,
                    'user'            => Auth::user()->name,
                    'user_id'         => Auth::user()->id,
                    'branch_id'       => Auth::user()->branch_id,
                    't_order'         => $time_now,
                    'd_order'         => $day_now,
                    'state'           => 1,
                    'shift'           =>$shift,
                    'min_charge'      =>$min_charge,
                    'gust'            =>$state_table->guest,
                    'tax_ratio'       => $tax,
                    'service_ratio'   => $service_ratio,
                    'discount_tax_service' =>$discount_tax_service,
                    'hold_list' =>0,
                    'gust'            =>1,
                ]);
              }
                $state_table = Table::where('branch_id',$branch_main )->where('number_table',$request->Table_ID)->limit(1)->select(['state','min_charge','guest'])->first();
                if($state_table->state == 0)
                {
                    $data_state_table = Table::where('branch_id',$branch_main )->where('number_table',$request->Table_ID)
                        ->limit(1)
                        ->update([
                            'state'=>1,
                            'booked_up'=>0,
                            'user_id' =>Auth::user()->id,
                            'user' =>Auth::user()->name,
                            'table_open' => $time_now,
                        ]);
                }

                $Quantity = 0;
                if(empty($request->Quantity))
                {
                    $Quantity = 1;
                }else{
                    $Quantity = $request->Quantity;
                }
                $group = Group::limit(1)->where('branch_id',Auth::user()->branch_id)
                    ->where('id',$request->subgroup_id)
                    ->get()
                    ->first();
                $data = Wait_order::create(
                    [
                      'item_id'            => $request->Item_ID,
                      'name'               => $request->Item_Name,
                      'table_id'           => $request->Table_ID,
                      'price'              => $request->Item_Price,
                      'order_id'           => $new_serial,
                      'sub_num_order'      => $new_order,
                      'subgroup_id'        => $group->id,
                      'subgroup_name'      => $group->name,
                      'total'              => $request->Item_Price * $Quantity,
                      'quantity'           => $Quantity,
                      'op'                 => 'Table',
                      'state'              => 1,
                      'user'               => Auth::user()->name,
                      'user_id'            => Auth::user()->id,
                      'branch_id'          => Auth::user()->branch_id,
                    ]
                );
                return response() ->json(['order'=>$new_serial,'item_id'=>$request->Item_ID]);
            }
        }
    }
    ################################## Delete Items Wait Orders #############################
    public function Delete_Order(Request $request)
    {
        $userId = Auth::user()->id;
        $userName = Auth::user()->name;
        $loginfo = array(
            'type' => 'delete item',
            'table'=>$request->table_id,
            'order'=>$request->Order_Number,
        );
        $this->LogInfo($loginfo);
        $branch = $this->GetBranch();
        $date = $this->Get_Date();

        $check_Order = Wait_order::limit(1)->where(['branch_id'=>$branch,'order_id'=>$request->Order_Number,'sub_num_order'=>$request->Order_ID])->first();
        if ($check_Order->status_take == '1')
        {
            $order_print = array('branch'=>$branch,'order_id'=>$request->Order_Number,'type'=>2,'item'=>$check_Order->item_id,'no_copies'=>1,'val_type'=>'Cancled','quan'=>$check_Order->quantity);
            $this->OrderPrint($order_print);

            $status_void = 'befor';
            $check_print = Orders_d::limit(1)->where('order_id',$request->Order_Number)->select(['no_print','serial_shift','d_order','t_order'])->first();
            $send_first_rep = array(
                'branch'    =>$branch,
                'val_type'  =>'Cancled',
                'quan'      =>$check_Order->quantity,
                'order_id'  =>$check_Order->order_id,
                'serial'    =>$check_print->serial_shift,
                'date'      =>$check_print->d_order,
                'time'      =>$check_print->t_order,
                'waiter'    =>$userName,
                'op'        =>$check_Order->op,
                'table_id'  =>$check_Order->table_id,
                'item'      =>$check_Order->name,
            );
            $this->SendToFirstRep($send_first_rep);

            if($check_print->no_print > 0) {
                $status_void = "after";
            }
            $create_void = Void_d::create([
                'order_id'          =>$check_Order->order_id,
                'date'              =>$date,
                'state'             =>$check_Order->state,
                'item_id'           =>$check_Order->item_id,
                'op'                =>$check_Order->op,
                'table_id'          =>$check_Order->table_id,
                'sub_num_order'     =>$check_Order->sub_num_order,
                'moved'             =>$check_Order->moved,
                'name'              =>$check_Order->name,
                'quantity'          =>$check_Order->quantity,
                'price'             =>$check_Order->price,
                'total'             =>$check_Order->total,
                'total_extra'       =>$check_Order->total_extra,
                'price_details'     =>$check_Order->price_details,
                'discount_name'     =>$check_Order->discount_name,
                'discount_type'     =>$check_Order->discount_type,
                'discount'          =>$check_Order->discount,
                'total_discount'    =>$check_Order->total_discount,
                'comment'           =>$check_Order->comment,
                'without'           =>$check_Order->without,
                'pick_up'           =>$check_Order->pick_up,
                'user'              =>$userName,
                'user_id'           =>$userId,
                'branch_id'         =>$check_Order->branch_id,
                'subgroup_id'       =>$check_Order->subgroup_id,
                'subgroup_name'     =>$check_Order->subgroup_name,
                'status'            =>$status_void,
            ]);
        }

        $getWaitOrderId = Wait_order::limit(1)->where(['branch_id'=>$branch,'order_id'=>$request->Order_Number,'sub_num_order'=>$request->Order_ID])->select(['id'])->first();

        $order_del = Wait_order::limit(1)->where(['branch_id'=>$branch,'order_id'=>$request->Order_Number,'sub_num_order'=>$request->Order_ID])->delete();
        DB::statement('ALTER TABLE wait_orders AUTO_INCREMENT = '.(count(Wait_order::all())+1).';');
        //delete Details Items
        $delete_details = Details_Wait_Order::where(['number_of_order'=>$request->Order_Number,'wait_order_id'=>$getWaitOrderId->id])->delete();
        DB::statement('ALTER TABLE details_wait_orders AUTO_INCREMENT = '.(count(Details_Wait_Order::all())+1).';');
         //delete Extra Items
        $delete_details = Extra_wait_order::where(['number_of_order'=>$request->Order_Number,'wait_order_id'=>$getWaitOrderId->id])->delete();
        DB::statement('ALTER TABLE extra_wait_orders AUTO_INCREMENT = '.(count(Extra_wait_order::all())+1).';');
        $this->AddTotalOrder($request->tr,$request->Order_Number);
        WithoutMaterialsD::where(['wait_order_id'=>$getWaitOrderId->id])->delete();
        if(Wait_order::where(['branch_id'=>$branch,'order_id'=>$request->Order_Number])->count() == 0 )
        {
            $checkAddOrder = Orders_d::limit(1)->where('order_id',$request->Order_Number)->select(['take_order'])->first();
            if($checkAddOrder->take_order == 1){
                $last_order = SerialCheck::where(['branch_id'=>$branch])->select(['order'])->orderBy('id', 'desc')->first();
                if($last_order->order == $request->Order_Number){
                    $del          = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->Order_Number])->delete();
                    $del_serial   = SerialCheck::where(['branch_id'=>$branch,'order'=>$request->Order_Number])->delete();
                    $del_sershift = SerialShift::limit(1)->where(['branch'=>$branch,'order_id'=>$request->Order_Number])->delete();
                }else{
                    $order_test = Item::limit(1)->where(['name'=>'TEST'])->first();
                    $group = Group::limit(1)->where('branch_id',Auth::user()->branch_id)
                        ->where('id',$order_test->group_id)
                        ->get()
                        ->first();
                    $time_now = $this->Get_Time();
                    if($request->table_id == ''){
                        $request->table_id = '#';
                    }
                    $data_test = Wait_order::create(
                        [
                            'item_id'            => $order_test->id,
                            'name'               => $order_test->name,
                            'price'              => $order_test->price,
                            'order_id'           => $request->Order_Number,
                            'table_id'           => $request->table_id,
                            'status_take'        => 1,
                            'pick_up'            => 1,
                            'sub_num_order'      => 1,
                            'subgroup_id'        => $group->id,
                            'subgroup_name'      => $group->name,
                            'total'              => $order_test->price,
                            'quantity'           => 1,
                            'op'                 => $request->tr,
                            'state'              => 0,
                            'user'               => Auth::user()->name,
                            'user_id'            => Auth::user()->id,
                            'branch_id'          => Auth::user()->branch_id,
                        ]
                    );
                    $this->AddTotalOrder($request->tr,$request->Order_Number);
                    $update_or = Orders_d::limit(1)->where('order_id',$request->Order_Number)->update([
                        'state' => 0,
                        'method'=>'cash',
                        'no_print'=>1,
                        'devcashier'=>$request->device,
                        't_closeorder'=>$time_now,
                    ]);
                }
                $update_state = Table::where(['branch_id'=>$branch,'number_table'=>$request->table_id])
                    ->update([
                        'state'      => 0,
                        'user'       => 0,
                        'user_id'    => 0,
                        'table_open' => 0
                    ]);
                $this->AddTotalOrder($request->tr,$request->Order_Number);
            }else{
                $update_state = Table::where(['branch_id'=>$branch,'number_table'=>$request->table_id])
                    ->update([
                        'state'      => 0,
                        'user'       => 0,
                        'user_id'    => 0,
                        'table_open' => 0
                    ]);
                $del          = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->Order_Number])->delete();
                $del_serial   = SerialCheck::where(['branch_id'=>$branch,'order'=>$request->Order_Number])->delete();
                $del_sershift = SerialShift::limit(1)->where(['branch'=>$branch,'order_id'=>$request->Order_Number])->delete();
            }
        }

    }
    ############################## Delete Items Wait OrdersM #############################
    public function Comment_Order(Request $request)
    {
        $loginfo = array(
            'type' => 'comment item',
            'order'=>$request->Order_Number,
        );
        $this->LogInfo($loginfo);
        $update_Order = Wait_order::where('order_id',$request -> Order_Number)
            ->where('sub_num_order',$request -> Order_ID)
            -> update([
                'comment' => $request ->text,
            ]);
        if($update_Order){
            return response()->json(['status'=>'true']);
        }
    }
    public function without_order(Request $request)
    {
        $get_wait_order = Wait_order::with(['Extra','Details','Without_m'])->limit(1)->where(['order_id'=>$request->Order_Number,'item_id'=>$request->Item,'sub_num_order'=>$request->Order_ID])->first();
        $total_extra       = 0 ;
        $new_extra         = 0 ;
        $old_extra         = 0 ;
        if($request->new_quant == $get_wait_order->quantity)
        {
            // loop for  materilas opject
            foreach($request->without_material as $row)
            {
                $materialRow = ComponentsItems::find($row['id']);
                $material = material::where(['code'=>$row['material_id']])->first();
                $unit = Units::whereName($material->unit)->first();
                $insert_extra = WithoutMaterialsD::create([
                    'number_of_order' => $request->Order_Number,
                    'material_id'     => $row['material_id'],
                    'price'           => $materialRow->cost,
                    'qty'             => $materialRow->quantity,
                    'qty_item'        => $request->new_quant,
                    'name'            => $materialRow->material_name,
                    'wait_order_id'   => $get_wait_order->id,
                    'item_id'         => $request->Item,
                    'total'           => $materialRow->quantity * $materialRow->cost * $request->new_quant,
                    'unit'            => $unit->sub_unit->name,
                    'pickup'          => 0

                ]);
            }
        }else{
            $get_new_sub = Wait_order::where('order_id',$request->Order_Number)->count() + 1;
            $one_details = $get_wait_order->price_details / $get_wait_order->quantity;
            $new_details = $one_details * $request->new_quant ;
            $old_details = $one_details * ($get_wait_order->quantity - $request->new_quant);
            $group = Sub_group::with('Group')
                ->where('branch_id',Auth::user()->branch_id)
                ->where('id',$request->subgroup_id)
                ->get()
                ->first();
            // New REcord same Items

            $new_recored = Wait_order :: create([
                'item_id'            => $get_wait_order->item_id,
                'table_id'           => $get_wait_order->table_id,
                'name'               => $get_wait_order->name,
                'price'              => $get_wait_order->price,
                'order_id'           => $request->Order_Number,
                'comment'            => $get_wait_order->comment,
                'without'            => $get_wait_order->without,
                'total'              => $get_wait_order->price * $request->new_quant,
                'discount'           => $get_wait_order->discount,
                'subgroup_id'        => $group->group->id,
                'subgroup_name'      => $group->group->name,
                'discount_name'      => $get_wait_order->discount_name,
                'discount_type'      => $get_wait_order->discount_type,
                'price_details'      => $new_details,
                'sub_num_order'      => $get_new_sub,
                'quantity'           => $request->new_quant,
                'user'               => Auth::user()->name,
                'user_id'            => Auth::user()->id,
                'branch_id'          => Auth::user()->branch_id,
                'op'                 =>'Table',
                'state'              =>'1',
                'status_take'        =>$get_wait_order->status_take,

            ]);

            $get_new_wait_order = Wait_order::where('order_id',$request->Order_Number)
            ->where('item_id',$request->Item)
            ->select(['id'])
            ->get()
            ->last();

            // //Copy same details in old order to new order
            foreach($get_wait_order->details as $details)
            {
                $insert_Details = Details_Wait_Order::create([
                    'number_of_order'  => $request->Order_Number,
                    'detail_id'        => $details->detail_id,
                    'price'            => $details->price,
                    'name'             => $details->name,
                    'wait_order_id'    => $get_new_wait_order->id,
                ]);
            }

            //Copy same extra in old order to new order
            foreach($get_wait_order->extra as $extra)
            {
                $insert_extra = Extra_wait_order::create([
                    'number_of_order' => $request->Order_Number,
                    'extra_id'        => $extra->extra_id,
                    'price'           => $extra->price,
                    'name'            => $extra->name,
                    'wait_order_id'   => $get_new_wait_order->id,
                ]);
                $old_extra = $old_extra + $extra->price;
            }
            foreach($get_wait_order->without_m as $without)
            {
                $insert_extra = WithoutMaterialsD::create([
                    'number_of_order' => $request->Order_Number,
                    'material_id'     => $without['id'],
                    'price'           => $without->price,
                    'qty'             => $without->qty,
                    'qty_item'        => $request->new_quant,
                    'name'            => $without->name,
                    'wait_order_id'   => $get_new_wait_order->id,
                    'item_id'         => $without->item_id,
                    'total'           => $without->qty * $without->price * $request->new_quant,
                    'unit'            => $without->unit,
                    'pickup'          => $without->pickup
                ]);
            }

            // add material with out
            // foreach($request->extraArray as $extra)
            // {
            //     $insert_extra = Extra_wait_order::create([
            //         'number_of_order' => $request->Order_Number,
            //         'extra_id'        => $extra['id'],
            //         'price'           => $extra['price'],
            //         'name'            => $extra['name'],
            //         'wait_order_id'   => $get_new_wait_order->id,
            //     ]);
            //     $new_extra = $new_extra + $extra['price'];
            // }

            // loop for  materilas opject
            foreach($request->without_material as $row)
            {
                $materialRow = ComponentsItems::find($row['id']);
                $material = material::where(['code'=>$row['material_id']])->first();
                $unit = Units::whereName($material->unit)->first();
                $insert_extra = WithoutMaterialsD::create([
                    'number_of_order' => $request->Order_Number,
                    'material_id'     => $row['material_id'],
                    'price'           => $materialRow->cost,
                    'qty'             => $materialRow->quantity,
                    'qty_item'        => $request->new_quant,
                    'name'            => $materialRow->material_name,
                    'wait_order_id'   => $get_new_wait_order->id,
                    'item_id'         => $request->Item,
                    'total'           =>$materialRow->quantity * $materialRow->cost * $request->new_quant,
                    'unit'            => $unit->sub_unit->name,
                    'pickup'          => 0
                ]);
            }

            $total_extra          = ($old_extra * $request->new_quant);
            $Quantity_old_item    = ($get_wait_order-> quantity) - ($request->new_quant);
            $total_extra_old_item = $Quantity_old_item * $old_extra;

            // variable Discount
            $type                 = $get_wait_order->discount_type ;
            $discount             = $get_wait_order->discount ;
            $new_total_dis        = 0 ;
            $old_total_dis        = 0 ;
            if($type  == "Ratio")
            {
                $math          = (($discount / 100) * $get_wait_order->price);
                $new_total_dis = $math * $request->new_quant;
                $old_total_dis = $math * $Quantity_old_item ;
            }
            elseif($type  == "Value")
            {
                $new_total_dis = $discount ;
                $old_total_dis = $discount ;
            }
            // Update table wait_order in mastre Order
            $old_wait = Wait_order::where('id',$get_wait_order->id)
                ->update([
                    'quantity'       => $Quantity_old_item,
                    'total_extra'    => $total_extra_old_item,
                    'price_details'  => $old_details,
                    'total'          => $Quantity_old_item * $get_wait_order->price,
                    'total_discount' => $old_total_dis
                ]);

            // Update table wait_order in slave Order
            // $new_wait = Wait_order::where('id',$get_new_wait_order->id)
            // ->update([
            //     'total_extra'    => $total_extra,
            //     'total_discount' => $new_total_dis
            // ]);
        }
        return response()->json([
            'status'=>true,
        ],200);
    }
    ##########################Start occupy table#######################################
    public function occupy_table(Request $request)
    {
        $data = Table::where('branch_id',$request->Branch_ID)->where('number_table',$request->number_of_tables)->update([
            'booked_up' => $request->occupy
        ]);
    }
    ###################################################################################
    ######################## Start Discount Items #####################################
    public function Discount_items(Request $request)
    {
        $loginfo = array(
            'type' => 'discount item',
            'order'=>$request->Order_Number,
            'note'=>$request->Name_Dis,
        );
        $this->LogInfo($loginfo);
        // Variable get in ajax
        $id            = $request -> ID_Discount;
        $value         = $request -> Val_Discount;
        $type          = $request -> Type_Discount;
        $input_val     = $request -> Input_value;
        $Order_ID      = $request -> Order_ID;
        $no_order      = $request -> Order_Number;
        $Name_Dis      = $request -> Name_Dis;
        $quantity      = $request -> new_quant;
        $item_price    = $request -> price;
        $Order_ID      = $request -> Order_ID;
        $total         = 0;
        $price         = 0;
        $new_sub_num_order = 0 ;
        $total_discount_back = 0;
        $check = Others::first()->allow_void;

        // Get Item
        $order = Wait_order::with(['Extra','Details'])
            ->where('order_id',$no_order )
            ->where('item_id',$request->Item)
            ->where('sub_num_order',$Order_ID)
            ->get()
            ->first();
        // $order_NO = $order -> Order_Number_dev ;
        // if type of discount Ratio
        function Ratio($type , $value , $Order_ID , $no_order , $Name_Dis , $total_discount)
        {
            $update_total = Wait_order::limit(1)->where('order_id',$no_order)
                -> where('sub_num_order',$Order_ID)->first();
            $data = Wait_order::where('order_id',$no_order)
                -> where('sub_num_order',$Order_ID)
                -> update([
                    'discount'         => $value,
                    'discount_type'    => $type,
                    'discount_name'    => $Name_Dis,
                    'total_discount'   => $total_discount,
                ]);
            $update_total = Wait_order::limit(1)->where('order_id',$no_order)
                    -> where('sub_num_order',$Order_ID)->first();

            $update_total->all_total = $update_total->total + $update_total->price_details + $update_total->total_extra - $total_discount;
            $update_total->save();
        }
        // if type of discount Value
        function Value($type , $Order_ID , $no_order , $discount , $Name_Dis)
        {
            $data = Wait_order::where('order_id',$no_order)
            -> where('sub_num_order',$Order_ID)
            -> update([
                'discount'          => $discount,
                'discount_type'     => $type,
                'discount_name'     => $Name_Dis,
                'total_discount'    => $discount,
            ]);
            $update_total = Wait_order::limit(1)->where('order_id',$no_order)
                -> where('sub_num_order',$Order_ID)->first();
            $update_total->all_total = $update_total->total + $update_total->price_details + $update_total->total_extra -  $discount;
            $update_total->save();
        }
        $getDetails  = 0 ;

        if($request->new_quant == $order-> quantity)
        {
            if($check == 1){
                $update_total = Wait_order::limit(1)->where('order_id',$no_order)
                    -> where('sub_num_order',$Order_ID)->first();
                $getDetails = Details_Wait_Order::where(['wait_order_id'=>$update_total->id])->sum('price');
            }
            $item_price += $getDetails;
            // check Type Ratio OR Value
            if($type == "Ratio")
            {
                $total_discount = ($item_price * ($value / 100)) * ($quantity);
                $total_discount_back = $total_discount;
                Ratio($type , $value , $Order_ID , $no_order , $Name_Dis , $total_discount);
            }
            elseif($type == "Value")
            {
                $discount = 0;
                if($input_val == '')
                {
                    $discount = $value;
                }else{
                    $discount = $input_val;
                }
                $total_discount_back = $discount;
                Value($type , $Order_ID , $no_order , $discount , $Name_Dis);
            }
        }else
        {
            // Create new recored
            $get_new_sub = Wait_order::where('order_id',$request->Order_Number)
                ->select(['sub_num_order'])
                ->get();

            foreach($get_new_sub as $new_ID)
            {
                if($new_ID->sub_num_order >= $new_sub_num_order)
                {
                    $new_sub_num_order = $new_ID->sub_num_order;
                }
            }

            $new_sub_num_order = $new_sub_num_order + 1;
             // On details Update
            $one_details = $order->price_details / $order->quantity;
            $new_details = $one_details * $request->new_quant ;
            $old_details = $one_details * ($order->quantity - $request->new_quant);

            // On Extra Update
            $one_extra = $order->total_extra / $order->quantity;
            $new_extra = $one_extra * $request->new_quant ;
            $old_extra = $one_extra * ($order->quantity - $request->new_quant);
            // New REcord same Items in wait item
            $group = Sub_group::with('Group')
                ->where('branch_id',Auth::user()->branch_id)
                ->where('id',$request->subgroup_id)
                ->get()
                ->first();
            $new_recored = Wait_order :: create([
                'item_id'            => $order   ->item_id,
                'table_id'           => $order   ->table_id,
                'name'               => $order   ->name,
                'price'              => $order   ->price,
                'order_id'           => $request ->Order_Number,
                'quantity'           => $request ->new_quant,
                'total'              => $order   ->price * $request->new_quant,
                'comment'            => $order   ->comment,
                'without'            => $order   ->without,
                'price_details'      => $new_details,
                'sub_num_order'      => $new_sub_num_order,
                'total_extra'        => $new_extra,
                'user'               => Auth::user()->name,
                'user_id'            => Auth::user()->id,
                'branch_id'          => Auth::user()->branch_id,
                'subgroup_id'        => $group->group->id,
                'subgroup_name'      => $group->group->name,
                'op'                 =>'Table',
                'state'              =>'1',
                'status_take'        =>$order->status_take,
                'all_total'          => ($order->price * $request->new_quant) + $new_extra + $new_details,
            ]);

            // Update table wait_order in slave Order
            $update_Extra = Wait_order::where('id',$order->id)
            ->update([
                'total_extra'    => $old_extra,
                'price_details'  => $old_details,
                'quantity'       => $order->quantity - $request->new_quant,
                'total'          => ($order->quantity - $request->new_quant) * $order->price,
            ]);

            // find new ID
            $get_new_wait_order = Wait_order::where('order_id',$request->Order_Number)
            ->where('item_id',$request->Item)
            ->select(['id'])
            ->get()
            ->last();
            // //Copy same details in old order to new order
            foreach($order->details as $details)
            {
                $insert_Details = Details_Wait_Order::create([
                    'number_of_order'  => $request->Order_Number,
                    'detail_id'        => $details->detail_id,
                    'price'            => $details->price,
                    'name'             => $details->name,
                    'wait_order_id'    => $get_new_wait_order->id,
                ]);
            }

            //Copy same extra in old order to new order
            foreach($order->extra as $extra)
            {
                $insert_extra = Extra_wait_order::create([
                    'number_of_order' => $request->Order_Number,
                    'extra_id'        => $extra->extra_id,
                    'price'           => $extra->price,
                    'name'            => $extra->name,
                    'wait_order_id'   => $get_new_wait_order->id,
                ]);
            }

            if($type == "Ratio")
            {
                $getDetails = 0;
                if($check == 1){
                    $update_total = Wait_order::limit(1)->where('order_id',$no_order)
                        -> where('sub_num_order',$Order_ID)->first();
                    $getDetails = Details_Wait_Order::where(['wait_order_id'=>$get_new_wait_order->id])->sum('price');
                }
                $item_price += $getDetails;
                $Order_ID = $new_sub_num_order;
                $total_discount = ($item_price * ($value / 100)) * ($request->new_quant);
                $total_discount_back = $total_discount;
                Ratio($type , $value , $Order_ID , $no_order , $Name_Dis , $total_discount);
            }
            elseif($type == "Value")
            {
                $discount = 0;
                if(empty($input_val))
                {
                    $discount = $value;
                }else{
                    $discount = $input_val;
                }
                $Order_ID = $new_sub_num_order;
                $total_discount_back = $discount;
                Value($type , $Order_ID , $no_order , $discount , $Name_Dis);
            }
        }
        $this->AddTotalOrder($request->op,$request->Order_Number);
        return response()->json(['discount'=>$total_discount_back]);
     }
    ######################## End Discount Items #####################################
    ######################## Strat Delete Discount Items #####################################
     public function delete_discount(Request $request)
     {
         $loginfo = array(
             'type' => 'delete discount item',
             'order'=>$request->Order_Number_,
             'note'=>$request->Discount,
         );
         $this->LogInfo($loginfo);
         $update_total = Wait_order::limit(1)->where('order_id',$request->Order_Number_)
             -> where('sub_num_order',$request->Order_ID)->first();
         $update_total->all_total = $update_total->total + $update_total->price_details + $update_total->total_extra;
         $update_total->discount = 0;
         $update_total->discount_name = null;
         $update_total->discount_type = null;
         $update_total->total_discount= 0;
         $update_total->save();
         $this->AddTotalOrder($request->op,$request->Order_Number_);
     }
    ######################## End Delete Discount Items #####################################
    ######################## Add Details in wiat order Items #####################################
    public function add_details_wait(Request  $request)
    {
        // get Order
        $branch = Auth::user()->branch_id;
        $wait_order = Wait_order::where(['branch_id'=>$branch])
            ->where('order_id',$request->Order_Number)
            ->select(['id','order_id','sub_num_order','total','quantity','item_id'])
            ->get()
            ->last();
        // insert details in table
        $price_details = 0;
        $loginfo = array(
            'type' => 'details item',
            'order'=>$request->Order_Number,
            'item'=>$wait_order->item_id,
        );
        $this->LogInfo($loginfo);
        if(!isset($request->detailsArray)){
            return "No Details";
        }
       foreach ($request -> detailsArray as $detail)
       {
           $create_new_details = Details_Wait_Order::create([
               'number_of_order'  => $request->Order_Number,
               'wait_order_id'    => $wait_order    -> id,
               'item_id'          => $wait_order->item_id,
               'detail_id'        => $detail["id"],
               'name'             => $detail["name"],
               'price'            => $detail["price"],

           ]);
           $price_details = $price_details + $detail["price"];
       }
       // Update price Details and Total
       $update_price = Wait_order::where('id',$wait_order->id)
        ->update([
                'price_details' => $price_details * $wait_order -> quantity,
        ]);
    }
    ######################## Add Details in wiat order Items #####################################
    ######################## start get extra in Items #####################################
    public function find_extra_item(Request $request)
    {
        $data = Item::with('Extra')
            ->where('id',$request->item)
            ->select(['id','group_id'])
            ->get();
        $count = sizeof($data[0]->extra);
        $materilas = ComponentsItems::where(['branch'=>Auth::user()->branch_id,'item_id'=>$request->item])->get();
        if($count == '0')
        {
            $data= extra::where(['group_id'=>$data[0]->group_id])->get();
            return response() ->json([
                'status' =>false,
                'data'   =>$data,
                'materilas' => $materilas
            ]);
        }else{
            return response() ->json([
                'status' =>true,
                'data'   =>$data,
                'materilas' => $materilas
            ]);
        }

    }
    ######################## end get extra in Items #####################################

    ########################## export_Extra in menu ######################################
    public function export_Extra(Request $request)
    {
        // all Local Variable in function
        $total_extra       = 0 ;
        $new_extra         = 0 ;
        $old_extra         = 0 ;
        $new_sub_num_order = 0 ;

        $get_wait_order = Wait_order::with(['Extra','Details'])
            ->where('order_id',$request->Order_Number)
            ->where('item_id',$request->Item)
            ->where('sub_num_order',$request->idItem)
            ->get();
        $loginfo = array(
            'type' => 'extra item',
            'order'=>$request->Order_Number,
            'item'=>$request->Item,
        );
        $this->LogInfo($loginfo);
        if($request->new_quant == $get_wait_order[0]-> quantity)
        {
            foreach($request->extraArray as $extra)
            {
                $insert_extra = Extra_wait_order::create([
                    'number_of_order' => $request->Order_Number,
                    'extra_id'        => $extra['id'],
                    'price'           => $extra['price'],
                    'name'            => $extra['name'],
                    'wait_order_id'   => $get_wait_order[0]->id,
                    'item_id'       =>$request->Item,
                ]);
                $total_extra = $total_extra + $extra['price'];
            }

            // update total extra in database
            $update_Extra = Wait_order::where('id',$get_wait_order[0]->id)
            ->update([
                'total_extra' => ($total_extra * $request->new_quant) + $get_wait_order[0]->total_extra,
            ]);

        }else{
            $get_new_sub = Wait_order::where('order_id',$request->Order_Number)
                ->select(['sub_num_order'])
                ->get();

            foreach($get_new_sub as $order)
            {
                if($order->sub_num_order >= $new_sub_num_order)
                {
                    $new_sub_num_order = $order->sub_num_order;
                }
            }

            $new_sub_num_order = $new_sub_num_order + 1;
            // $new_details = 0;
            // $old_details = 0;

            $one_details = $get_wait_order[0]->price_details / $get_wait_order[0]->quantity;
            $new_details = $one_details * $request->new_quant ;
            $old_details = $one_details * ($get_wait_order[0]->quantity - $request->new_quant);
            $group = Sub_group::with('Group')
                ->where('branch_id',Auth::user()->branch_id)
                ->where('id',$request->subgroup_id)
                ->get()
                ->first();
            // New REcord same Items
            $new_recored = Wait_order :: create([
                'item_id'            => $get_wait_order[0]->item_id,
                'table_id'           => $get_wait_order[0]->table_id,
                'name'               => $get_wait_order[0]->name,
                'price'              => $get_wait_order[0]->price,
                'order_id'           => $request->Order_Number,
                'comment'            => $get_wait_order[0]->comment,
                'without'            => $get_wait_order[0]->without,
                'total'              => $get_wait_order[0]->price * $request->new_quant,
                'discount'           => $get_wait_order[0]->discount,
                'subgroup_id'        => $group->group->id,
                'subgroup_name'      => $group->group->name,
                'discount_name'      => $get_wait_order[0]->discount_name,
                'discount_type'      => $get_wait_order[0]->discount_type,
                'price_details'      => $new_details,
                'sub_num_order'      => $new_sub_num_order,
                'quantity'           => $request->new_quant,
                'user'            => Auth::user()->name,
                'user_id'         => Auth::user()->id,
                'branch_id'       => Auth::user()->branch_id,
                'op'              =>'Table',
                'state'              =>'1',
                'status_take'        =>$get_wait_order[0]->status_take,

            ]);

            $get_new_wait_order = Wait_order::where('order_id',$request->Order_Number)
            ->where('item_id',$request->Item)
            ->select(['id'])
            ->get()
            ->last();

            // //Copy same details in old order to new order
            foreach($get_wait_order[0]->details as $details)
            {
                $insert_Details = Details_Wait_Order::create([
                    'number_of_order'  => $request->Order_Number,
                    'detail_id'        => $details->detail_id,
                    'price'            => $details->price,
                    'name'             => $details->name,
                    'wait_order_id'    => $get_new_wait_order->id,
                ]);
            }

            //Copy same extra in old order to new order
            foreach($get_wait_order[0]->extra as $extra)
            {
                $insert_extra = Extra_wait_order::create([
                    'number_of_order' => $request->Order_Number,
                    'extra_id'        => $extra->extra_id,
                    'price'           => $extra->price,
                    'name'            => $extra->name,
                    'wait_order_id'   => $get_new_wait_order->id,
                ]);
                $old_extra = $old_extra + $extra->price;
            }

            // add new extra in new item
            foreach($request->extraArray as $extra)
            {
                $insert_extra = Extra_wait_order::create([
                    'number_of_order' => $request->Order_Number,
                    'extra_id'        => $extra['id'],
                    'price'           => $extra['price'],
                    'name'            => $extra['name'],
                    'wait_order_id'   => $get_new_wait_order->id,
                ]);
                $new_extra = $new_extra + $extra['price'];
            }
            $total_extra          = ($old_extra * $request->new_quant)+($new_extra *$request->new_quant);
            $Quantity_old_item    = ($get_wait_order[0]-> quantity) - ($request->new_quant);
            $total_extra_old_item = $Quantity_old_item * $old_extra;
            // Update Discount
            // variable Discount
            $type                 = $get_wait_order[0]->discount_type ;
            $discount             = $get_wait_order[0]->discount ;
            $new_total_dis        = 0 ;
            $old_total_dis        = 0 ;
            if($type  == "Ratio")
            {
                $math          = (($discount / 100) * $get_wait_order[0]->price);
                $new_total_dis = $math * $request->new_quant;
                $old_total_dis = $math * $Quantity_old_item ;
            }
            elseif($type  == "Value")
            {
                $new_total_dis = $discount ;
                $old_total_dis = $discount ;
            }
            // Update table wait_order in mastre Order
            $old_wait = Wait_order::where('id',$get_wait_order[0]->id)
                ->update([
                    'quantity'       => $Quantity_old_item,
                    'total_extra'    => $total_extra_old_item,
                    'price_details'  => $old_details,
                    'total'          => $Quantity_old_item * $get_wait_order[0]->price,
                    'total_discount' => $old_total_dis
                ]);

            // Update table wait_order in slave Order
            $new_wait = Wait_order::where('id',$get_new_wait_order->id)
            ->update([
                'total_extra'    => $total_extra,
                'total_discount' => $new_total_dis
            ]);
        }
    }
    ########################## export_Extra in menu ######################################
    ########################## strat change Menu ######################################
    public function change_menu(Request $request)
    {
        $new_menu = Group::where('branch_id',$request->branch)
            ->where('menu_id',$request->menu)
            ->get();
        return response()->json($new_menu);
    }
    ########################## end change menu ######################################
    ########################## end change menu ######################################
    public function take_order(Request $request)
    {
        $branch = Auth::user()->branch_id;
        $data_order = [];
        $time_now = $this->Get_Time();
        $shift = $this->Shift();
        $log_table = 0;
        $order_print = array('branch'=>$branch,'order_id'=>$request->order,'type'=>1,'no_copies'=>1,'val_type'=>'New');
        if(Wait_order::where(['branch_id'=>$branch,'order_id'=>$request->order,'pick_up'=>1])->count() > 0){
            $order_print['val_type'] = 'Added';
        }
        // Check Of All Order In Ketchin
        if(Wait_order::where(['branch_id'=>$branch,'order_id'=>$request->order,'pick_up'=>0])->count() > 0){
            if(Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order,'op'=>'Delivery','customer_name'=>null])->count() > 0){
                return response()->json(['status'=>'none_customer']);
            }
            $calop = $this->calculate_taxandservice($request->op , $request->order);
            $alltotal = 0;
            $alltotal = $calop[0]['total'] + $calop[0]['service'] + $calop[0]['tax'];
            switch ($request->op) {
                case 'Delivery':
                    {
                    $order = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order])->limit(1)
                        ->update([
                            'to_pilot'=>1,
                            'take_order'=>1,
                            'hold_list'=>0,
                            'sub_total'=>$calop[0]['total'],
                            'tax'=>$calop[0]['tax'],
                            'tax_ratio'=>$calop[0]['tax_ratio'],
                            'services'=>$calop[0]['service'],
                            'service_ratio'=>$calop[0]['service_ratio'],
                            'total'=>$alltotal,
                            'shift'=>$shift,
                            "t_order"=>$time_now,
                        ]);
                        $wait = Wait_order::where(['branch_id'=>$branch,'order_id'=>$request->order])
                            ->update([
                                'status_take' => 1
                            ]);
                    }
                break;
                case 'TO_GO':
                {
                        $order = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order])->limit(1)
                            ->update([
                            'delivery_order'=>1,
                            'take_order'=>1,
                            'table'=>'#' . $request->togo_table,
                            'sub_total'=>$calop[0]['total'],
                            'tax'=>$calop[0]['tax'],
                            'tax_ratio'=>$calop[0]['tax_ratio'],
                            'services'=>$calop[0]['service'],
                            'service_ratio'=>$calop[0]['service_ratio'],
                            'total'=>$alltotal,
                             'shift'=>$shift,
                             "t_order"=>$time_now,
                            ]);
                        $wait = Wait_order::where(['branch_id'=>$branch,'order_id'=>$request->order])
                            ->update([
                                'status_take' => 1
                            ]);
                }break;
                default:
                {

                    $order = Orders_d::where(['branch_id'=>$branch,'order_id'=>$request->order])->limit(1)
                        ->update([
                            'take_order'=>1,
                            'sub_total'=>$calop[0]['total'],
                            'tax'=>$calop[0]['tax'],
                            'tax_ratio'=>$calop[0]['tax_ratio'],
                            'services'=>$calop[0]['service'],
                            'service_ratio'=>$calop[0]['service_ratio'],
                            'total'=>$alltotal,
                            'shift'=>$shift,
                            "t_order"=>$time_now,
                        ]);
                    $order_t = Orders_d::limit(1)->where(['branch_id'=>$branch,'order_id'=>$request->order])->select(['table'])->first();

                    $log_table = $order_t->table;

                        $wait = Wait_order::where(['branch_id'=>$branch,'order_id'=>$request->order])
                            ->update([
                                'status_take' => 1
                            ]);
                } break;
            }
            $this->OrderPrint($order_print);
            $this->SerialShift($request->order);
            $this->CheckPrintWait($request->order);
            $this->AddTotalWait($request->order);
            $this->AddTotalOrder($request->op,$request->order);
            $loginfo = array(
                'type' => 'take order',
                'order'=>$request->order,
                'table'=>$log_table,
                'note'=>$request->op,
            );
            $this->LogInfo($loginfo);
            return response()->json(['status'=>'true']);
        }else{
            return response()->json(['status'=>'none_order']);
        }
    }
    ########################## end change menu ######################################
    ########################## Start Discount all ###################################
    public function Discount_all(Request $request)
    {
        if($request->Val_Discount == '' && $request->Input_value == '')
        {
            return response()->json(['status'=>'false']);
        }else{
            $branch = Auth::user()->branch_id;
            $id            = $request -> ID_Discount;
            $value         = $request -> Val_Discount;
            $type          = $request -> Type_Discount;
            $input_val     = $request -> Input_value;
            $no_order      = $request -> Order_Number;
            $Name_Dis      = $request -> Name_Dis;
            $val_me_all    = $request->val_me_all;
            function Ratio($no_order ,$type , $value , $Name_Dis , $input_val , $branch , $val_me_all){
                if($type == 'Ratio'){
                    $order = Orders_d::limit(1)
                        ->where(['branch_id'=>$branch,'order_id'=>$no_order])->first();
                    if($input_val == ''){
                        $order->discount = $value;
                        $order->discount_name = $Name_Dis;
                        $order->discount_type = $type;
                        $order->total_discount = ($val_me_all * $value)/100;
                        $order->total  -= ($val_me_all * $value)/100;
                    }else{
                        $order->discount = $input_val;
                        $order->discount_name = $Name_Dis;
                        $order->discount_type = $type;
                        $order->total_discount = ($val_me_all * $input_val)/100;
                    }
                    $order->save();
                }else{
                    $order = Orders_d::limit(1)
                        ->where(['branch_id'=>$branch,'order_id'=>$no_order])->first();
                    $order->discount = $input_val;
                    $order->discount_name = 'Discount';
                    $order->discount_type = $type;
                    $order->total_discount = $input_val;
                    $order->total  -= $input_val;
                    $order->save();
                }
                return $no_order;
            }
            $data = Ratio($no_order ,$type , $value , $Name_Dis , $input_val , $branch , $val_me_all);
            $this->AddTotalOrder($request->op,$request->Order_Number);

            if($data)
            {
                $loginfo = array(
                    'type' => 'discount order',
                    'order'=>$request->Order_Number,
                    'note'=>$request->Name_Dis,
                );
                $this->LogInfo($loginfo);
                return response()->json(['status'=>'true']);
            }
        }
    }
    ########################## end change menu ######################################
    public function CheckService(Request $request)
    {
        $branch = Auth::user()->branch_id;
        $data = 0;
        if($request->op == "service"){
            if($request->active == '1')
            {
                $data = 0;
            }else{
                $check = Service_tables::limit(1)
                    ->where('branch',$branch)
                    ->select(['service_ratio'])
                    ->first();
                $data = $check->service_ratio;
            }
            $data = Orders_d::where(['order_id'=>$request->order])
            ->update([
                'state_service' => $request->active,
                'service_ratio' => $data,
            ]);

        }elseif($request->op =="tax"){
            if($request->active == '1')
            {
                $data = 0;
            }else{
                $check = Service_tables::limit(1)
                    ->where('branch',$branch)
                    ->select(['tax'])
                    ->first();
                $data = $check->tax;
            }
            $data = Orders_d::where(['order_id'=>$request->order])
            ->update([
                'state_tax' => $request->active,
                'tax_ratio' => $data,
            ]);
        }
        $this->AddTotalOrder($request->operation,$request->order);

    }
}

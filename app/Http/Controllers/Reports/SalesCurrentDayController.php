<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Item;
use App\Models\menu;
use App\Models\System;
use App\Models\Orders_d;
use App\Models\Sub_group;
use App\Models\Wait_order;
use App\Traits\All_Functions;
use App\Traits\All_Notifications_menu;
use Illuminate\Http\Request;

class SalesCurrentDayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Notifications_menu;
    use All_Functions;

    public function cashier_report(Request $request){
        $branch       = $this->GetBranch();
        $orders       = [];
        $wait_orders  = [];
        $extract_data = [];
        $all_discount_items = 0;
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;

        $menu = menu::limit(1)->where(['branch_id'=>$branch , 'active'=>1])->first();
        $group = Group::where(['branch_id'=>$branch])->select(['name'])->get();

        if($request->type == "cashier_report"){
            $orders = Orders_d::where(['branch_id'=>$branch , 'state'=>0])->get();
        }elseif ($request->type == "busy_order"){
            $orders = Orders_d::where(['branch_id'=>$branch , 'state'=>1])->get();
        }
        // Filter By Transaction
        if(isset($request->trans)){
            $orders = $orders->whereIn('op',$request->trans);
        }

        // Filter By Bay-Way
        $hosVar = 0;
        if(isset($request->bay_way)){
            $size = sizeof($request->bay_way);
            if($size == 1){
                if($request->bay_way[0] == "hospitality"){$hosVar = 1;}
            }
            $orders = $orders->whereIn('method',$request->bay_way);
        }
        // Filter By Shift
        if(isset($request->shift)){
            $orders = $orders->whereIn('shift',$request->shift);
        }
        // Filter By Users
        if(isset($request->user)){
            $orders = $orders->whereIn('user_id',$request->user);
        }
        // Filter By Devices
        if(isset($request->device)){
            $orders = $orders->whereIn('devcashier',$request->device);
        }
        if(isset($request->addition))
        {
            // Filter By Additions
            if($request->addition == 'state_service'){
                $orders = $orders->whereIn('state_service',1);
            }
            elseif ($request->addition == 'state_tax'){
                $orders = $orders->whereIn('state_tax',1);
            }
        }
        // Get Wait Order

        foreach ($orders as $order){
            $wait_orders = Wait_order::where(['branch_id'=>$branch , 'order_id'=>$order->order_id])->select([
                'order_id','total','total_extra','price_details','total_discount','subgroup_name'
            ])->get();
            foreach ($wait_orders as $wait){
                if (isset($extract_data[$wait->subgroup_name])){
                    $extract_data[$wait->subgroup_name] += $wait->total + $wait->total_extra+ $wait->price_details;
                    $all_discount_items +=  $wait->total_discount;
                }else{
                    $extract_data[$wait->subgroup_name] = $wait->total + $wait->total_extra+ $wait->price_details;
                    $all_discount_items +=  $wait->total_discount;
                }
                foreach ($extract_data as $ex => $val){
                    if($order->hos == 1 && $hosVar == 0){
                        $order->total = 0;
                        $order->cash = 0;
                        // $order->sub_total = 0;
                    }
                    $order[$ex]=$val;
                }
            }
            $order['total_discount'] += $all_discount_items;
            $order['sub_total'] += $all_discount_items;
            $extract_data = [];
            $order->hos == 1 ? $order->hosp_total = $order->sub_total - $all_discount_items : $order->hosp_total = 0;
            $all_discount_items = 0;
            if($order->hos == 1){

            }
        }
        return response()->json([
            'orders'=>$orders,
            'group' =>$group,
            'report_type'=>$request->type,
            'res_nmae' =>$res_name,
        ]);
    }
    public function cashier_report_sold(Request $request){
        $branch       = $this->GetBranch();
        $orders       = [];
        $wait_orders  = [];
        $extract_data = [];
        $counter      = 0;
        $group        = [];
        $cont_fake    = 0;
        $count_ex     = 0;
        $system_data  = System::limit(1)->first();
        $res_name     = $system_data->name;

        if($request->type == "cashier_sold"){
            $orders = Orders_d::where(['branch_id'=>$branch , 'state'=>0])->get();
        }elseif ($request->type == "busy_sold"){
            $orders = Orders_d::where(['branch_id'=>$branch , 'state'=>1])->get();
        }

        // Filter By Transaction
        if(isset($request->trans)){
            $orders = $orders->whereIn('op',$request->trans);
        }
        // Filter By Bay-Way
        if(isset($request->bay_way)){
            $orders = $orders->whereIn('method',$request->bay_way);
        }
        // Filter By Shift
        if(isset($request->shift)){
            $orders = $orders->whereIn('shift',$request->shift);
        }
        // Filter By Users
        if(isset($request->user)){
            $orders = $orders->whereIn('cashier',$request->user);
        }
        // Filter By Devices
        if(isset($request->device)){
            $orders = $orders->whereIn('devcashier',$request->device);
        }
        if(isset($request->addition))
        {
            // Filter By Additions
            if($request->addition == 'state_service'){
                $orders = $orders->whereIn('state_service',1);
            }
            elseif ($request->addition == 'state_tax'){
                $orders = $orders->whereIn('state_tax',1);
            }
        }

        // Get Wait Order
        $menu = menu::limit(1)->where(['branch_id'=>$branch , 'active'=>1])->first();
        $group_d = Group::with('Supgroups')->where(['branch_id'=>$branch])->get();

        foreach ($group_d as $gr){
            $count = 0;
            $counter = sizeof($group);
            $group[$counter] = array(
                'id'    =>$gr->id,
                'name'  =>$gr->name
            );
            foreach ($gr->Supgroups as $sub){
                $group[$counter]['sub_group'][] = array(
                    'id'    =>$sub->id,
                    'name'  =>$sub->name,
                );
            }
        }

        foreach ($orders as $order) {
            $wait_orders = Wait_order::with(['Extra','Details'])->where(['branch_id' => $branch, 'order_id' => $order->order_id,'status_take'=>1])->get();
            foreach ($wait_orders as $wait){
                $group_h = $wait->subgroup_id;
                $get_subgroup = Item::limit(1)->where(['branch_id'=>$branch,'id'=>$wait->item_id])->select(['sub_group_id'])->first();
                $sub_group_h = $get_subgroup->sub_group_id;
                $price = $wait->total;
                $gr_count = sizeof($group);
                for($gr = 0 ; $gr < $gr_count ; $gr++){
                    if($group[$gr]['id'] == $group_h){
                        $sub_count = sizeof($group[$gr]['sub_group']);
                        for($sub = 0 ; $sub < $sub_count ; $sub++){
                            if($group[$gr]['sub_group'][$sub]['id'] == $sub_group_h){
                                if(isset($group[$gr]['sub_group'][$sub]['sold'])){
                                    $item_count = 0;
                                    $flag = 0;
                                    $item_count = sizeof($group[$gr]['sub_group'][$sub]['sold']);
                                    for($it = 0 ; $it < $item_count ; $it++){
                                        if($group[$gr]['sub_group'][$sub]['sold'][$it]['id'] == $wait->item_id){
                                            $group[$gr]['sub_group'][$sub]['sold'][$it]['quan']+=$wait->quantity;
                                            $group[$gr]['sub_group'][$sub]['sold'][$it]['price']+=$price;

                                            //Check of Extra  in items
                                            if(isset($wait->extra)){
                                                foreach($wait->extra as $ex){
                                                    if(isset($group[$gr]['sub_group'][$sub]['sold'][$it]['extra'])){
                                                        $count_ex = sizeof($group[$gr]['sub_group'][$sub]['sold'][$it]['extra']);
                                                        $cont_fake = 0;
                                                        for($i = 0 ; $i < $count_ex ; $i++){
                                                            if($group[$gr]['sub_group'][$sub]['sold'][$it]['extra'][$i]['id'] == $ex->extra_id){
                                                                $group[$gr]['sub_group'][$sub]['sold'][$it]['extra'][$i]['price'] += $wait->quantity * $ex->price;
                                                                $group[$gr]['sub_group'][$sub]['sold'][$it]['extra'][$i]['quan'] += $wait->quantity;
                                                            }else{
                                                                $cont_fake++;
                                                            }
                                                        }
                                                        if($cont_fake == $count_ex){
                                                            $group[$gr]['sub_group'][$sub]['sold'][$it]['extra'][] = array(
                                                                'id'=>$ex->extra_id,
                                                                'name'=>$ex->name,
                                                                'price'=>$wait->quantity * $ex->price,
                                                                'quan'=>$wait->quantity,
                                                            );
                                                            $cont_fake = 0;
                                                        }
                                                    }else{
                                                        $group[$gr]['sub_group'][$sub]['sold'][$it]['extra'][] = array(
                                                            'id'=>$ex->extra_id,
                                                            'name'=>$ex->name,
                                                            'price'=>$wait->quantity * $ex->price,
                                                            'quan'=>$wait->quantity,
                                                        );
                                                    }

                                                }
                                            }
                                            //Check of Details  in items
                                            if(isset($wait->details)){
                                                foreach($wait->details as $dl){
                                                    if(isset($group[$gr]['sub_group'][$sub]['sold'][$it]['details'])){
                                                        $count_ex = sizeof($group[$gr]['sub_group'][$sub]['sold'][$it]['details']);
                                                        $cont_fake = 0;
                                                        for($i = 0 ; $i < $count_ex ; $i++){
                                                            if($group[$gr]['sub_group'][$sub]['sold'][$it]['details'][$i]['id'] == $dl->detail_id){
                                                                $group[$gr]['sub_group'][$sub]['sold'][$it]['details'][$i]['price'] += $wait->quantity * $dl->price;
                                                                $group[$gr]['sub_group'][$sub]['sold'][$it]['details'][$i]['quan'] += $wait->quantity;
                                                            }else{
                                                                $cont_fake++;

                                                            }
                                                        }
                                                        if($cont_fake == $count_ex){
                                                            $group[$gr]['sub_group'][$sub]['sold'][$it]['details'][] = array(
                                                                'id'=>$dl->detail_id,
                                                                'name'=>$dl->name,
                                                                'price'=>$wait->quantity * $dl->price,
                                                                'quan'=>$wait->quantity,
                                                            );
                                                            $cont_fake = 0;
                                                        }
                                                    }else{
                                                        if($cont_fake == $count_ex){
                                                            $group[$gr]['sub_group'][$sub]['sold'][$it]['details'][] = array(
                                                                'id'=>$dl->detail_id,
                                                                'name'=>$dl->name,
                                                                'price'=>$wait->quantity * $dl->price,
                                                                'quan'=>$wait->quantity,
                                                            );
                                                        }
                                                    }
                                                }
                                            }
                                        }else{
                                            $flag += 1;
                                        }
                                    }
                                    if($flag == $item_count){
                                        $group[$gr]['sub_group'][$sub]['sold'][] = array(
                                            'id'=>$wait->item_id,
                                            'name'=>$wait->name,
                                            'quan'=>$wait->quantity,
                                            'price'=>$price,
                                        );
                                        $flag = 0;
                                        $con_now = sizeof($group[$gr]['sub_group'][$sub]['sold']);
                                        $con_now--;
                                        if(isset($wait->extra)){
                                            foreach ($wait->extra as $ex){
                                                    $group[$gr]['sub_group'][$sub]['sold'][$con_now]['extra'][] = array(
                                                    'id'=>$ex->extra_id,
                                                    'name'=>$ex->name,
                                                    'price'=>$wait->quantity * $ex->price,
                                                    'quan'=>$wait->quantity,
                                                );
                                            }
                                            if(isset($wait->details)){
                                                foreach ($wait->details as $de){
                                                    $group[$gr]['sub_group'][$sub]['sold'][$con_now]['details'][] = array(
                                                        'id'=>$de->detail_id,
                                                        'name'=>$de->name,
                                                        'price'=>$wait->quantity * $de->price,
                                                        'quan'=>$wait->quantity,
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    $group[$gr]['sub_group'][$sub]['sold'][0] = array(
                                        'id'=>$wait->item_id,
                                        'name'=>$wait->name,
                                        'quan'=>$wait->quantity,
                                        'price'=>$price,
                                    );
                                    if(isset($wait->extra)){
                                        foreach ($wait->extra as $ex){
                                            $group[$gr]['sub_group'][$sub]['sold'][0]['extra'][] = array(
                                                'id'=>$ex->extra_id,
                                                'name'=>$ex->name,
                                                'price'=>$wait->quantity * $ex->price,
                                                'quan'=>$wait->quantity,
                                            );
                                        }
                                    }
                                    if(isset($wait->details)){
                                        foreach ($wait->details as $de){
                                            $group[$gr]['sub_group'][$sub]['sold'][0]['details'][] = array(
                                                'id'=>$de->detail_id,
                                                'name'=>$de->name,
                                                'price'=>$wait->quantity * $de->price,
                                                'quan'=>$wait->quantity,
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return response()->json([
            'orders'=>$group,
            'report_type'=>$request->type,
            'res_nmae' =>$res_name,
        ]);
   }
}

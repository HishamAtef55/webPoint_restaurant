<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Wait_order;
use App\Models\Orders_d;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use App\Traits\All_Functions;
use App\Models\Service_tables;
use App\Models\ToGo;
use App\Models\Delavery;


class PayController extends Controller
{
    use All_Functions;
    public function pay (Request $request)
    {
        if($request->order == null){
            return  response()->json(['status'=>'empty_order']);
        }
        $branch = $this->GetBranch();
        $rest = 0;
        $total_min = 0;
        if($request->table == null){
            $total_min = 0;
        }else{
            $data = Table::where('branch_id',$branch)
                ->where('number_table',$request->table)->select(['min_charge','guest'])->get()->first();
            $total_min = $data->min_charge * $data->guest ;
        }


        if($total_min > $request->totalPrice )
        {
            $rest = $total_min - $request->totalPrice ;
            return response()->json([
                "status"      => "min",
                "rest"        => $rest,
                "min_charge"  =>$total_min
            ]);
        }else if($total_min <= $request->totalPrice || $request->totalPrice == null)
        {
            $order = Orders_d::limit(1)->where('order_id',$request->order)->get();
            $dis_name   = $order[0]->discount_name;
            $dis_type   = $order[0]->discount_type;
            $dis        = $order[0]->discount;
            $delivery   = $order[0]->delivery;
            $dis_val    = 0;
            $total_wait = 0;
            $cash       = $order[0]->cash;
            $visa       = $order[0]->visa;
            $value_bank = $order[0]->r_bank;
            $type       = $order[0]->method;
            $Orders   = Wait_order::where('branch_id',$branch)
                ->where('order_id',$request->order)->get();

            $r_bank  = array('r_bank'=>0);
            $r_bank = Service_tables::where('branch',$branch)->select(['r_bank'])->first();
            $op_cal = $order[0]->op;
            $taxandservice = $this->calculate_taxandservice($op_cal , $request->order);

            if($order[0]->no_print > 0){
                return response()->json([
                    "status"      => true,
                    "rest"        => $rest,
                    "min_charge"  => $total_min,
                    "data"        => $Orders,
                    "service"     => $taxandservice,
                    "discount"    => $order[0]->total_discount,
                    "delivery"    => $order[0]->delivery,
                    "bank_ratio"  => $r_bank->r_bank,
                    'cash'        =>$order[0]->cash,
                    'visa'        =>$order[0]->visa,
                    'value_bank'  =>$order[0]->r_bank,
                    'type'        =>$type,
                    'total'       =>$order[0]->total,
                    'tip'       =>$order[0]->tip
                ]);
            }else{
                foreach ($Orders  as $ser)
                {
                    $total_wait = $total_wait + $ser->total + $ser->total_extra + $ser->price_details - $ser->total_discount;
                }
                if($order[0]->discount_type == 'Ratio')
                {
                    $cal_ratio = ($order[0]->discount / 100) * $total_wait;
                    $dis_val =$cal_ratio;
                    $dis_val = bcadd($dis_val,'0',2);
                }elseif ($order[0]->discount_type == 'Value')
                {
                    $dis_val = $order[0]->discount;
                    $dis_val = bcadd($dis_val,'0',2);
                }

                // ############### End Order Summary ###################

                // ############## Get Ration Bank ########################

                return response()->json([
                    "status"      => true,
                    "rest"        => $rest,
                    "min_charge"  => $total_min,
                    "data"        => $Orders,
                    "service"     => $taxandservice,
                    "discount"    => $dis_val,
                    "delivery"    => $delivery,
                    "bank_ratio"  => $r_bank->r_bank,
                    'cash'        =>$cash,
                    'visa'        =>$visa,
                    'value_bank'  =>$value_bank,
                    'type'        =>$type,
                    'total'       =>$order[0]->total,
                    'tip'       =>$order[0]->tip

                ]);
            }
        }
    }

    public function pay_check(Request $request)
    {
        /* Set Time Now */
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $cash = 0;
        $visa = 0;
        $sep  = $request->total;
        if($request->bank_value > 0){
            $sep  = $request->total + $request->bank_value;
        }

        /* Get Casher */
        $branch = $this->GetBranch();
        $cashier = $this->GetUser();
        /*Increase of number of print */
        /* Sepration Tip */
        if((int) $sep == $sep)
        {
            $tip = 0;
        }else{
            $arr = explode(".", $sep);
            $tip = '0.'. $arr[1];
            $tip = 1 - $tip;
        }

        /*Check of Operations  */
        $state = 0;
        $del = 0;
        if($request->operation == "Delivery")
        {
            $del = 1;
        }
        /* check of Method */

        /* check of Method */
        $hos = 0 ;   // Default of Hosbatility
        $ser_ratio = $request->serviceratio;
        $ser = $request->service;
        $tax = $request->tax;
        $sub_to = $request->subtotal;
        switch($request->method_bay){
            case 'cash':{
                $cash = $sep;
                $visa = 0;
            }break;
            case 'credit':{
                $cash = $sep - $request->price;
                $visa = $request->price;
            }break;
            case 'hospitality':{
                $cash = 0;
                $visa = 0;
                $hos  = 1;
                $tip  = 0;
                $ser = 0;
                $tax = 0;
                $extras = 0;
                $details = 0;
            }break;
        }
        $details = 0;
        $extras  = 0;
        $w_orders = Wait_order::with(['Extra','Details'])->where(['order_id'=>$request->order])->get();

        foreach ($w_orders as $order){
            //Extra
            $extraWait = $order->extra->sum('price') * $order->quantity ;
            $order->total_extra =  $extraWait;
            $extras += $extraWait;
            //Details
            $detailsaWait = $order->details->sum('price') * $order->quantity ;
            $order->price_details =  $detailsaWait;
            $details += $detailsaWait;
            $order->save();
        }
        $dateReal = $this->CheckDayOpen();
        /* Update Order And pay */
        $updateorder = Orders_d::limit(1)->where('order_id',$request->order)
            ->update([
                'total_discount'  => $request->discount,
                'sub_total'       => $sub_to,
                'total'           => $sep,
                'cashier'         => $cashier,
                'service_ratio'   => $ser_ratio,
                'services'        => $ser,
                'tax'             => $tax,
                'method'          => $request->method_bay,
                'devcashier'      => $request->device,
                'total_extra'     => $extras,
                'total_details'   => $details,
                'tip'             => $tip,
                'cash'            => $cash,
                'visa'            => $visa,
                't_closeorder'    => $time_now,
                'state'           => 0,
                'r_bank'          =>$request->bank_value,
                'hos'             =>$hos,
                'pilot_account'   =>0,
                'delivery_order'  =>$del,
                'd_order'         =>$dateReal
            ]);
        $orderCheck = Orders_d::whereOrderId($request->order)->first();
        $this->AddTotalOrder($orderCheck->op,$request->order);
        if($updateorder){
            $Orders = Wait_order::where('branch_id',$branch)
                ->where('order_id',$request->order)
                ->update([
                    'state' => 0,
                ]);
            $data_table = Table::with(['mainHole'])->where(['branch_id'=>$branch,'number_table'=>$request->table])->first();
            if(isset($data_table->mainHole) && $data_table->mainHole->name == "Other"){
                $deleteOtherTable = Table::where(['branch_id'=>$branch,'number_table'=>$request->table])->delete();
            }else{
                $table =  Table::where(['branch_id'=>$branch , 'number_table'=>$request->table])
                    ->update([
                        'state'      => 0,
                        'guest'      => 0,
                        'master'     => 0,
                        'follow'     => 0,
                        'user_id'    => 0,
                        'printcheck' => 0,
                        'user'       => null,
                    ]);
            }
            $updateorder = Orders_d::limit(1)->where('order_id',$request->order)->first();
            $loginfo = array(
                'type' => 'end table',
                'table'=> $updateorder->table,
                'note' => 'end Order to table number ' . $updateorder->table ." in order " . $updateorder->order_id,
                'order'=> $updateorder->order_id,
                'op'   => $updateorder->op,
                'time' => $this->Get_Time(),
                'date' => $updateorder->d_order,
              );
            $this->LogInfo($loginfo);
            $table =  Table::where(['branch_id'=>$branch , 'follow'=>$request->table])
                ->update([
                    'follow' => 0,
                    'merged' => 0,
                ]);
            $this->deleteTransfer($request->table);
            return response()->json(['status' => 'true']);
        }
    }

    public function print_check(Request $request){
        /* Set Time Now */
        $table_log = 0;
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $cash = 0;
        $visa = 0;
        $shift = $this->Shift();
        $sep  = $request->total;
        /* Get Casher */
        $branch = $this->GetBranch();
        $cashier = Auth::user()->id;
        /*Increase of number of print */
        $Print = Orders_d::limit(1)->where('order_id',$request->order)
            ->select(['no_print','dev_id','table','order_id','op','d_order'])->first();

        $NoPrint = $Print->no_print + 1;

        $color_table = 1;
        if($NoPrint == 1){
            $color_table = 2;
        }elseif($NoPrint > 1){
            $color_table = 3;
        }
        /* Sepration Tip */
        if((int) $sep == $sep)
        {
            $tip = 0;
        }else{
            $arr = explode(".", $sep);
            $tip = '0.'. $arr[1];
            $tip = 1 - $tip;
        }
        $details = 0;
        $extras  = 0;
        $w_orders = Wait_order::with(['Extra','Details'])->where(['order_id'=>$request->order])->get();

        foreach ($w_orders as $order){
            //Extra
            $extraWait = $order->extra->sum('price') * $order->quantity ;
            $order->total_extra =  $extraWait;
            $extras += $extraWait;
            //Details
            $detailsaWait = $order->details->sum('price') * $order->quantity ;
            $order->price_details =  $detailsaWait;
            $details += $detailsaWait;
            $order->save();
        }
        /* check of Method */
        $hos = 0 ;   // Default of Hosbatility
        $ser_ratio = $request->serviceratio;
        $ser = $request->service;
        $tax = $request->tax;
        $sub_to = $request->subtotal;
        $total = $request->total;
        if(!isset($request->method_bay)){$request->method_bay = 'Cash';}
        switch($request->method_bay){
            case 'cash':{
                $cash = $sep;
                $visa = 0;
                $total = $request->total;
            }break;
            case 'credit':{
                if($request->price > $sep){
                    $visa = $sep;
                    $tip += $request->price - $sep;
                    $cash = 0;
                }else{
                    $cash = $sep - $request->price;
                    $visa = $request->price;
                }
                if($cash < 1){$cash = 0;}
            }break;
            case 'hospitality':{
                $cash = 0;
                $visa = 0;
                $hos  = 1;
                $tip = 0;
                $updateorder = Orders_d::limit(1)->where('order_id',$request->order)
                    ->update([
                        'sub_total'  => 0,
                        'services'   => 0,
                        'tax'        => 0,
                    ]);
            }break;
        }
        $endcheck = 1;
        if($request->operation == "TO_GO"){
            $endcheck = 0;
        }
        /* Update Order And pay */
        $updateorder_p = Orders_d::limit(1)->where('order_id',$request->order)->first();
        if($updateorder_p->op = "Delivery"){
            $total = $updateorder_p->total;
        }
        $dateReal = $this->CheckDayOpen();
        $updateorder = Orders_d::limit(1)->where('order_id',$request->order)
            ->update([
                'no_print'        => $NoPrint,
                'cashier'         => $cashier,
                'method'          => $request->method_bay,
                'devcashier'      => $request->device,
                'tip'             => $tip,
                'cash'            => $cash,
                'visa'            => $visa,
                't_closeorder'    => $time_now,
                'r_bank'          =>$request->bank_value,
                'hos'             =>$hos,
                'state'           =>$endcheck,
                'total_extra'     => $extras,
                'total_details'   => $details,
                'total_discount'  => $request->discount,
                'total'           => $total,
                'shift'           => $shift,
                'd_order'         =>$dateReal,
            ]);

            if($request->table != null){
                $table =  Table::where(['branch_id'=>$branch , 'number_table'=>$request->table])
                    ->update([
                        'printcheck' => $NoPrint,
                        'state'      => $color_table,
                    ]);
                $table_log = $request->table;
            }
            $type_check = 0;
            $no_copies  = 0;
            $printer    = null;
            $device_print = Device::limit(1)->where(['branch_id'=>$branch,'id_device'=>$Print->dev_id])->first();
            switch($request->operation){
                case 'Table':{
                    $ex = Service_tables::limit(1)->where(['branch'=>$branch])->select(['invoic_copies'])->first();
                    $type_check = 3;
                    $printer = $device_print->printer_invoice;
                    $no_copies = $ex->invoic_copies;
                }break;
                case 'Delivery':{
                    $type_check = 4;
                    $ex = Delavery::limit(1)->where(['branch'=>$branch])->select(['printer','pilot_copies'])->first();
                    $printer = $device_print->printer_invoice;
                    $no_copies = $ex ->pilot_copies;
                }break;
                case 'TO_GO':{
                    $type_check = 3;
                    $ex = ToGo::limit(1)->where(['branch'=>$branch])->select(['printer','invoice_copies'])->first();
                    $printer = $device_print->printer_invoice;
                    $no_copies = $ex ->invoice_copies;
                }break;
            }
            $order_print = array(
                'branch'=>$branch,
                'order_id'=>$request->order,
                'type'=>$type_check,
                'no_copies'=>$no_copies ,
                'printer'=>$printer
            );
            $loginfo = array(
                'type' => 'print order',
                'table'=> $Print->table,
                'note' => 'print '. $NoPrint .' in Order to table number ' . $Print->table ." in order " . $Print->order_id,
                'order'=> $Print->order_id,
                'op'   => $Print->op,
                'time' => $this->Get_Time(),
                'date' => $Print->d_order,
              );
            $this->LogInfo($loginfo);
            $this->OrderPrint($order_print);
    }
}

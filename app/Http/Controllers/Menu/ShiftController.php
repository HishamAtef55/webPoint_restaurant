<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\Days;
use App\Traits\All_Functions;
use App\Traits\All_Notifications_menu;
use App\Models\Details_Wait_Order;
use App\Models\Details_Wait_Order_m;
use App\Models\Extra_wait_order;
use App\Models\Extra_wait_order_m;
use App\Models\Orders_d;
use App\Models\Orders_m;
use App\Models\Void_d;
use App\Models\Void_m;
use App\Models\Wait_order;
use App\Models\Wait_order_m;
use App\Models\SerialShift;
use App\Models\Service_tables;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Notifications_menu;
    use All_Functions;
    public function close_shift(Request $request){
        $branch = $this->GetBranch();
        $date   = $this->CheckDayOpen();
        $this->calcBeforeCloseShift();
        if($request->type == 'close_day'){
            return $this->EndDay();
        }elseif($request->type == "close_shift"){
            if(Orders_d::where(['branch_id'=>$branch])->count() == 0)
            {
                return response()->json([
                    'status'=>'error',
                    'msg'=>'Shift cannot be closed This shift is new.',
                ]);
            }
            if(Orders_d::where(['state' => '1','branch_id'=>$branch])->where('no_print','>','0')->count() > 0){
                return response()->json([
                    'status'=>'error',
                    'msg'=>'Shift cannot be closed There are open orders',
                ]);
            }else{
                $shift       = Shift::limit(1)->where(['branch_id'=>$branch,'status'=>1])->select(['shiftid'])->first();
                $last_shift  = Shift::where(['branch_id'=>$branch])->max('shiftid');
                $first_shift = Shift::where(['branch_id'=>$branch])->min('shiftid');
                $new_shift   = 1;
                if($shift->shiftid >= $first_shift && $shift->shiftid < $last_shift){
                    $new_shift += $shift->shiftid;
                }elseif($shift->shiftid == $last_shift){
                      $new_shift = $shift->shiftid;
                }
                $del_shift = Orders_d::where(['state' => '0','branch_id'=>$branch])->select(['order_id'])->get();
                foreach($del_shift as $del){
                    $this->reCalcOrder($del->order_id);
                    SerialShift::limit(1)->where(['branch'=>$branch,'order_id'=>$del->order_id])->delete();
                }
                $this->OrderNotTake();
                $this->ReportShift();
                $update_shift = Shift::limit(1)->where(['branch_id'=>$branch,'shiftid'=>$shift->shiftid])->update(['status'=>0]);
                $update_new_shift = Shift::limit(1)->where(['branch_id'=>$branch,'shiftid'=>$new_shift])->update(['status'=>1]);
                $update = Days::limit(1)->where(['branch'=>$branch,'active'=>1])->update([
                    'last_shift'=>$new_shift,
                ]);
                // $del_shift = Orders_d::where(['state' => '0','branch_id'=>$branch])->select(['order_id'])->get();
                // foreach($del_shift as $del){
                //     SerialShift::limit(1)->where(['branch'=>$branch,'order_id'=>$del->order_id])->delete();
                // }
                if($update_new_shift && $update_shift){
                    $get_printer = Service_tables::where(['branch'=>$branch])->first();
                    $order_print = array('branch'=>$branch,'order_id'=>'0','type'=>9,'no_copies'=>1,'val_type'=>'0','printer'=>$get_printer->printer_shift);
                    $this->OrderPrint($order_print);
                    $order_close = Orders_d::where(['state' => '0','branch_id'=>$branch])->update([
                        'shift_status'=>0,
                    ]);
                    return response()->json([
                        'status'=>'success',
                        'msg'=>'Shift Is Closed And Open Another Shift',
                    ]);
                }else{
                    return response()->json([
                        'status'=>'error',
                        'msg'=>'Shift cannot be closed',
                    ]);
                }
            }
        }
    }
}

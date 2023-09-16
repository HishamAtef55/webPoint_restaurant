<?php
namespace App\Traits;
use App\Models\Customer_wait_order;
use App\User;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Table;
use App\Models\Wait_order;
use App\Models\OrdersM;
use App\Models\Orders_d;
use App\Traits\All_Functions;

use Illuminate\Support\Facades\Auth;


Trait All_Notifications_menu
{
    use All_Functions;
    function Delivery()
    {
        $order_delivery = [];
        date_default_timezone_set('Africa/Cairo');
        $day_now = $this->CheckDayOpen();
        $order_delivery = Orders_d::where('branch_id',Auth::user()->branch_id)
            ->where('op','Delivery')
            ->where('state','1')
            ->where('d_order',$day_now)
            ->count();
        return $order_delivery;
    }
    function Delivery_to_pilot()
    {
        $order_delivery = [];
        date_default_timezone_set('Africa/Cairo');
        $day_now = $this->CheckDayOpen();
        $order_delivery = Orders_d::where('branch_id',Auth::user()->branch_id)
            ->where('op','Delivery')
            ->where('to_pilot',1)
            ->where('d_order',$day_now)
            ->count();
        return $order_delivery;
    }
    function Delivery_hold()
    {
        $order_delivery = [];
        date_default_timezone_set('Africa/Cairo');
        $day_now = $this->CheckDayOpen();
        $order_delivery = Orders_d::where('branch_id',Auth::user()->branch_id)
            ->where('op','Delivery')
            ->where('hold_list',1)
            ->where('d_order',$day_now)
            ->count();
        return $order_delivery;
    }
    function Delivery_pilot()
    {
        $order_delivery = [];
        date_default_timezone_set('Africa/Cairo');
        $day_now = $this->CheckDayOpen();
        $order_delivery = Orders_d::where('branch_id',Auth::user()->branch_id)
            ->where('op','Delivery')
            ->where('pilot_account',1)
            ->where('d_order',$day_now)
            ->count();
        return $order_delivery;
    }
    function TOGO_hold()
    {
        $order_delivery = [];
        date_default_timezone_set('Africa/Cairo');
        $day_now = $this->CheckDayOpen();
        $order_delivery = Orders_d::where('branch_id',Auth::user()->branch_id)
            ->where('op','TO_GO')
            ->where('hold_list',1)
            ->where('d_order',$day_now)
            ->count();
        return $order_delivery;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\Customer_wait_order;
use App\Models\Group;
use App\Models\Hole;
use App\Models\Item;
use App\Models\Orders_m;
use App\Models\Printers;
use App\Models\SerialCheck;
use App\Models\Wait_order;
use App\Models\Wait_order_m;
use App\Traits\All_Notifications_menu;
use App\Traits\All_Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use All_Notifications_menu;
    use All_Functions;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
//        $this->deleteOrderRep();
        $user = Auth::user();
//        $this->orderLate();
        $this->removeActionTable();
        $data = array(
            'type' => 'login',
            'note' => 'User is login now',
        );
        $this->LogInfo($data);
        $this->CheckDay();
        $this->CheckLastOrder();


        $holes = Hole::where('branch_id',$user->branch_id)->get();
        $branch = $user->branch_id;
        $order_delivery = 1;
        $transfers = $this->checkTransfers();
        $to_noti_hold      = $this->TOGO_hold();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();
        $printers = Printers::where(['active'=>'1'])->get();
        return view('menu.tables',compact(['transfers','printers','to_noti_hold','user','holes','branch','order_delivery','del_noti','del_noti_to_pilot','del_noti_pilot','del_noti_hold']));
    }
}

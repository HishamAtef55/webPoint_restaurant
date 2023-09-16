<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Branch;
use App\Traits\All_Notifications_menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Notifications_menu;
    public function moveto()
    {
        $this->CheckLastOrder();
        $to_noti           = $this->TOGO();
        $to_noti_order     = $this->Togo_Order();
        $to_noti_hold      = $this->TOGO_hold();
        $del_noti          = $this->Delivery();
        $del_noti_to_pilot = $this->Delivery_to_pilot();
        $del_noti_pilot    = $this->Delivery_pilot();
        $del_noti_hold     = $this->Delivery_hold();

        $tables = Table::where('branch_id',Auth::user()->branch_id)->get();
        return view('menu.moveto',compact
        ([
            'tables',
            'del_noti',
            'del_noti_to_pilot',
            'del_noti_pilot',
            'del_noti_hold',
        ]));
    }
}

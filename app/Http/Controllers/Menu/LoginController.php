<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Sub_group;
use App\Models\Table;
use App\Models\System;
use App\Models\Branch;
use App\Models\Hole;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Traits\All_Functions;
use App\Http\Controllers\HomeController;
use App\Traits\All_Notifications_menu;
use App\Models\Printers;

class LoginController extends Controller
{
    use All_Functions;
    use All_Notifications_menu;
    public function view_login(Request $request)
    {
        $get = System::get()->all();
        $users = User::select(['email'])->get()->all();
        return view('auth/login',compact('get','users'));
    }
    public function check_admin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $access_system = json_decode( Auth::user()->access_system) ?? [];
            $flagAccess = 0;
            foreach($access_system as $access){
                if($request->type == $access){
                    $flagAccess = 1;
                }
            }
            if($flagAccess == 0){
                $users = User::select(['email'])->get()->all();
                Auth::logout();
                return view('auth.login',compact('users'))->withErrors(['message' => $request->type . " " . 'هذا المستخدم لايجوز له الدخول الي ']);
            }
            switch ($request->type)
            {
              case 'pos':
                    //$this->deleteOrderRep();
                    $user = Auth::user();
                    //$this->orderLate();
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
                    return view('menu.tables',compact([
                        'transfers','printers','to_noti_hold','user','holes','branch',
                        'order_delivery','del_noti','del_noti_to_pilot','del_noti_pilot','del_noti_hold']));
                break;
                case 'stock':
                  return view('stock.stock.home');
              default:
                // code...
                break;
            }
        }
    }

    public function logout()
    {
        $this->removeActionTable();
        Auth::logout();
        return view('auth.login');
    }
}

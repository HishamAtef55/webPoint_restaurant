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
                    return redirect()->route("home");
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

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


class LoginController extends Controller
{
    use All_Functions;
    public function view_login(Request $request)
    {
        $get = System::get()->all();
        $users = User::select(['email'])->get()->all();
        return view('auth/login',compact('get','users'));
    }
    public function check_admin(Request $request)
    {

        $email = $request ->user;
        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            return response()->json(['success'=>false, 'message' => 'Not Login successfull']);
        }
        if (!Hash::check($request->pass, $user->password)) {
            return response()->json(['success'=>false, 'message' => 'Not Login successfull']);
        }
        //return response()->json(['success'=>true,'message'=>'success', 'data' => $user]);
        $data = ['success'=>true,'message'=>'success', 'data' => $user];
        $holes = Hole::where('branch_id',$user->branch_id)->get();
        $branch = $user->branch_id;
        $user_ = Auth::User();
        Session::put('user', $user);
        $user_=Session::get('user');

        switch ($request->type)
        {
          case '1':
              return view('menu.tables',compact(['user','holes','branch']));
            break;
            case '3':
              $branchs = Branch::get()->all();
              return view('control.welcome',compact(['user','branchs']));
              break;
          default:
            // code...
            break;
        }

    }

    public function logout()
    {
        $this->removeActionTable();
        Auth::logout();
        return view('auth.login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\All_Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    use All_Functions;
    public  function logout(Request $request)
    {
        $data = array(
            'type' => 'logout',
            'note' => 'User is logout now',
        );
        $this->LogInfo($data);
        $this->removeActionTable();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $users = \App\Models\User::select(['email'])->get();
        return view('auth.login',compact('users'));
    }
}

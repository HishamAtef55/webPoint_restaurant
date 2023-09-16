<?php

namespace App\Http\Controllers\menu;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Traits\All_Notifications_menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TogoController extends Controller
{
    use All_Notifications_menu;
    public function __construct()
    {
        $this->middleware('auth');
    }
}

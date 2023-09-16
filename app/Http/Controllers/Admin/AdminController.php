<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\ItemsRequest;
use App\Http\Requests\JobRequest;
use App\Http\Requests\MenuRequest;
use App\Http\Requests\SubGroupRequest;
use App\Models\Branch;
use App\Models\Details;
use App\Models\Group;
use App\Models\Item;
use App\Models\Hole;
use App\Models\Job;
use App\Models\Locations;
use App\Models\menu;
use App\Models\Table;
use App\Models\Sub_group;
use App\Models\Printers;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');
    }

    ##################################### Start View Control Page ###########################
    public function view_printers(){
        $branchs = Branch::get()->all();
        return view('control.printers',compact('branchs'));
    }

    public function reset_data(){
        $branchs = Branch::get()->all();
        return view('control.reset_data',compact('branchs'));
    }
    public function View_General()
    {
        $branchs = Branch::get()->all();
        return view('control.welcome',compact('branchs'));
    }
    public function View_AddItem()
    {
        $branchs = Branch::get()->all();
        return view('control.add_item',compact('branchs'));
    }
    //include Add User Page
    public function View_AddUser()
    {
        $jobs = Job::get()->all();
        $branchs = Branch::get()->all();
        return view('control.add_User',compact('branchs','jobs'));
    }
    //include Add Item Detalis Page
    public function View_ItemsDetails()
    {
        $branchs = Branch::get()->all();
        return view('control.add_Items_extra',compact('branchs'));
    }
    //include Add Discount Page
    public function View_Discount()
    {
        $branchs = Branch::get()->all();
        return view ('control.add_Discount',compact('branchs'));
    }
    //include Add ServicesGeneral Page
    public function View_ServicesGeneral()
    {
        return view('control.add_ServicesGeneral');
    }
    //include Add View CarServices Page
    public function View_CarServices()
    {
        return view('control.add_CarServices');
    }
    //include Add View Delivery Page
    public function View_Delivery()
    {
        return view('control.add_Delivery');
    }
    //include Add View Tabels Page
    public function View_Tabels()
    {
        return view('control.add_Tables');
    }
    //include Add View TakeAway Page
    public function View_TakeAway()
    {
        return view('control.add_TakeAway');
    }
    //include Add View Other Page
    public function View_Other()
    {
        return view('control.add_Other');
    }
    // include Add New Details
    public function View_Add_details()
    {
        $branchs = Branch::get()->all();
        return View('control.Add_new_details',compact('branchs'));
    }

    public function Add_Tables()
    {
        $branchs = Branch::get()->all();
        return view('control.add_Tables',compact('branchs'));
    }
    public function Add_Extra()
    {
        $branchs = Branch::get()->all();
        return view('control.add_extra',compact('branchs'));
    }
    public function add_location()
    {
        $branchs = Branch::get()->all();
        return view('control.add_locations',compact('branchs'));
    }
    public function add_shift(){
        $branchs = Branch::get()->all();
        return view('control.add_shift',compact('branchs'));
    }
    ##################################### End View Control Page #########################

}

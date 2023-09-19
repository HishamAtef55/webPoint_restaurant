<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Locations;
use App\Models\SerialCheck;
use App\Models\Customer_phone;
use App\Models\Orders_d;
use App\Models\Delavery;
use App\Models\TOGO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\All_Functions;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    use All_Functions;
    public function Save_customer(CustomerRequest $request)
    {
        if($request->location == ''){
            $request->location = "0";
        }
        if($request->street == ''){
            $request->street = "0";
        }
        if($request->address == ''){
            $request->address = "0";
        }
        if($request->role == ''){
            $request->role = "0";
        }

        if($request->department == ''){
            $request->department = "0";
        }


        if($request->special_marque == ''){
            $request->special_marque = "0";
        }
        if($request->notes == ''){
            $request->notes = "0";
        }

        foreach ($request->phone as $ph){
            if($ph == null){
                return response()->json(['status'=>'false','msg'=>'Please Enter Phone']);
            }
        }

        $location = Locations::limit(1)->where('id',$request->location)->first();
        $insertcustomer = Customer::insertGetId([
            'branch_id'         =>$request->branch,
            'name'              =>$request->name,
            'location_id'       =>$request->location,
            'location'          =>$location->location,
            'street'            =>$request->street,
            'address'           =>$request->address,
            'role'              =>$request->role,
            'department'        =>$request->department,
            'special_marque'    =>$request->special_marque,
            'notes'             =>$request->notes,
            'created_at' =>'2022-09-26 21:40:54',
            'updated_at' => '2022-09-26 21:40:54',
        ]);
        foreach($request->phone as $ph)
        {
            $insertphone = Customer_phone::create([
                'branch_id'   => $request->branch,
                'customer_id' => $insertcustomer,
                'phone'       => $ph
            ]);
        }
        $order_p = '';
        $order = $this->get_new_serial($request->branch , $order_p , $request->device);
        date_default_timezone_set('Africa/Cairo');
        $time_now = date(' H:i');
        $day_now = $this->CheckDayOpen();
        $data = Orders_d::create([
            'branch_id' => $request->branch,
            'customer_id'      => $insertcustomer,
            'delivery'      => $location->price,
            'customer_name'    => $request->name,
            'order_id'    => $order,
            'dev_id'      =>$request->device,
            'table'       =>0,
            't_order'     => $time_now,
            'd_order'     => $day_now,
            'user_id'     => Auth::user()->id,
            'user'        => Auth::user()->name,
            'location'    => $request->location,
            'op'              => "Delivery",
            'state'           => 1,
            'to_pilot'        => 1,
            'hold_list'       => 0,
            'time_hold_list'  =>null,
            'date_holde_list' =>null,
            'delivery_order'  =>0,
        ]);
        if($insertcustomer)
        {
            return response()->json([
                'status' => true,
                'order' =>$order
            ]);
        }

    }

    public function search_customer(Request $request)
    {
        $query = $request->get('query');
        switch($request->type)
        {
            case 'search_name':
                {
                    $data = Customer::with('Phones')
                        ->where('branch_id', $request->ID)
                        ->where('name', 'LIKE', '%' . $query . "%")
                        ->get();
                }
            break;
            case 'search_location':
                {
                    $data = Customer::with('Phones')
                        ->where('branch_id', $request->ID)
                        ->where('location', 'LIKE', '%' . $query . "%")
                        ->get();
                }
            break;
            case 'search_phone':
                {
                    $data = Customer_phone::with('Customer')
                        ->where('branch_id', $request->ID)
                        ->where('phone', 'LIKE', '%' . $query . "%")
                        ->get();
                }
            break;
            default :
                {
                    $data = Customer_phone::with('Customer')
                    ->where('branch_id', $request->ID)
                    ->where('phone', 'LIKE', '%' . $query . "%")
                    ->get();
                }
        }


        return response()->json($data);
    }

    public function update_customer (Request $request)
    {
        if($request->location == ''){
            $request->location = "0";
        }
        if($request->street == ''){
            $request->street = "0";
        }
        if($request->address == ''){
            $request->address = "0";
        }
        if($request->role == ''){
            $request->role = "0";
        }

        if($request->department == ''){
            $request->department = "0";
        }


        if($request->special_marque == ''){
            $request->special_marque = "0";
        }
        if($request->notes == ''){
            $request->notes = "0";
        }
        $location = Locations::limit(1)->where('id',$request->location)->first();

        $up_cus = Customer::where('id',$request->id)
            ->update([
                'name'              =>$request->name,
                'location_id'       =>$request->location,
                'location'          =>$location->location,
                'street'            =>$request->street,
                'address'           =>$request->address,
                'role'              =>$request->role,
                'department'        =>$request->department,
                'special_marque'    =>$request->special_marque,
                'notes'             =>$request->notes,
            ]);
        $insertphone = Customer_phone::where('id',$request->id_phone)
              ->update([
                    'phone'       => $request->phone[0]
                ]);
        if($up_cus)
        {
            return response()->json([
                'status' => true
            ]);
        }
    }

    public function order_customer(Request $request)
    {
        $location_get = Locations::limit(1)->where('id',$request->location)
        ->first();
        $price_location = $location_get->price ;

        if ($request->state == 'New_customer')
        {
                if(Orders_d::where('branch_id',Auth::user()->branch_id)
                        ->where('order_id',$request->order_id)
                        ->limit(1)
                        ->count() > 0)
                {
                    $branch = Auth::user()->branch_id;
                    $check = Delavery::limit(1)
                        ->where('branch',$branch)
                        ->select(['discount_tax_service','tax','ser_ratio'])
                        ->first();
                    $discount_tax_service = $check->discount_tax_service;
                    $tax                  = $check->tax;
                    $service_ratio        = $check->ser_ratio;

                    $data = Orders_d::where('branch_id', Auth::user()->branch_id)
                        ->where('order_id', $request->order_id)
                        ->update([
                            'customer_id'   => $request->customer_id,
                            'customer_name' => $request->customer,
                            'location'      => $request->location,
                            'delivery'      => $price_location,
                            'tax_ratio'        => $tax,
                            'service_ratio'    => $service_ratio,
                            'discount_tax_service' =>$discount_tax_service,
                            'dev_id'           =>$request->dev,
                            'table'            =>0,
                            'user_id'          => Auth::user()->id,
                            'user'             => Auth::user()->name,
                            'op'               => "Delivery",
                            'state'            => 1,
                            'to_pilot'            => 1,
                            'hold_list'            => 0,
                            'time_hold_list'            =>null,
                            'date_holde_list'            =>null,
                            'delivery_order'            =>0,
                        ]);
                    if ($data) {
                        return response()->json(['status' => true,'order'=>$request->order_id]);
                    }
                }else{
                    $shift = $this->Shift();
                    $branch = Auth::user()->branch_id;
                    $order = $this->get_new_serial($branch , $request->order_id , $request->dev);
                    date_default_timezone_set('Africa/Cairo');
                    $time_now = date(' H:i');
                    $day_now = $this->CheckDayOpen();
                    $check = Delavery::limit(1)
                        ->where('branch',$branch)
                        ->select(['discount_tax_service','tax','ser_ratio'])
                        ->first();
                    $discount_tax_service = $check->discount_tax_service;
                    $tax                  = $check->tax;
                    $service_ratio        = $check->ser_ratio;
                    $data = Orders_d::create([
                        'branch_id'        => $branch,
                        'customer_id'      => $request->customer_id,
                        'customer_name'    => $request->customer,
                        'order_id'         => $order,
                        'dev_id'           =>$request->dev,
                        'table'            =>0,
                        't_order'          => $time_now,
                        'd_order'          => $day_now,
                        'user_id'          => Auth::user()->id,
                        'user'             => Auth::user()->name,
                        'location'         => $request->location,
                        'op'               => "Delivery",
                        'state'            => 1,
                        'shift'            =>$shift,
                        'tax_ratio'        => $tax,
                        'service_ratio'    => $service_ratio,
                        'delivery'         => $price_location,
                        'discount_tax_service' =>$discount_tax_service,
                        'to_pilot'            => 1,
                        'hold_list' =>0

                    ]);
                    if ($data) {
                        return response()->json(['status' => true,'order'=>$order , 'delivery'=>$price_location]);
                    }
                }

        }
        elseif ($request->state == 'Edit_customer')
        {
            $data = Orders_d::limit(1)->where('branch_id', Auth::user()->branch_id)
                ->where('order_id', $request->order_id)
                ->select('op')->first();
            if($data->op  == "TO_GO"){
                $check_order = Orders_d::where('branch_id',Auth::user()->branch_id)
                        ->where('order_id',$request->order_id)
                        ->select(['hold_list','time_hold_list','date_holde_list'])
                        ->limit(1)->first();

            if($check_order->count() > 0)
            {

                $to_pilot             = 1;
                $hold_list            = 0;
                $time_hold_list       =null;
                $date_holde_list      =null;
                if($check_order->hold_list == 1){
                    $to_pilot             = 0;
                    $hold_list            = 1;
                    $time_hold_list       =$request->time_hold_list;
                    $date_holde_list      =$request->date_holde_list;
                }
                $branch = Auth::user()->branch_id;
                $check = Delavery::limit(1)
                    ->where('branch',$branch)
                    ->select(['discount_tax_service','tax','ser_ratio'])
                    ->first();
                $discount_tax_service = $check->discount_tax_service;
                $tax                  = $check->tax;
                $service_ratio        = $check->ser_ratio;

                $data = Orders_d::where('branch_id', Auth::user()->branch_id)
                    ->where('order_id', $request->order_id)
                    ->update([
                        'customer_id'   => $request->customer_id,
                        'customer_name' => $request->customer,
                        'location'      => $request->location,
                        'delivery'      => $price_location,
                        'tax_ratio'        => $tax,
                        'service_ratio'    => $service_ratio,
                        'discount_tax_service' =>$discount_tax_service,
                        'dev_id'           =>$request->dev,
                        'table'            =>0,
                        'user_id'          => Auth::user()->id,
                        'user'             => Auth::user()->name,
                        'op'               => "Delivery",
                        'state'            => 1,
                        'to_pilot'            => $to_pilot,
                        'hold_list'            => $hold_list,
                        'time_hold_list'            =>$time_hold_list,
                        'date_holde_list'            =>$date_holde_list,
                        'delivery_order'            =>0,
                    ]);
                if ($data) {
                    return response()->json(['status' => true,'order'=>$request->order_id]);
                }
            }else{
                    $shift = $this->Shift();
                    $branch = Auth::user()->branch_id;
                    $order = $this->get_new_serial($branch , $request->order_id , $request->dev);
                    date_default_timezone_set('Africa/Cairo');
                    $time_now = date(' H:i');
                    $day_now = $this->CheckDayOpen();
                    $check = Delavery::limit(1)
                        ->where('branch',$branch)
                        ->select(['discount_tax_service','tax','ser_ratio'])
                        ->first();
                    $discount_tax_service = $check->discount_tax_service;
                    $tax                  = $check->tax;
                    $service_ratio        = $check->ser_ratio;
                    $data = Orders_d::create([
                        'branch_id'        => $branch,
                        'customer_id'      => $request->customer_id,
                        'customer_name'    => $request->customer,
                        'order_id'         => $order,
                        'dev_id'           =>$request->dev,
                        'table'            =>0,
                        't_order'          => $time_now,
                        'd_order'          => $day_now,
                        'user_id'          => Auth::user()->id,
                        'user'             => Auth::user()->name,
                        'location'         => $request->location,
                        'op'               => "Delivery",
                        'state'            => 1,
                        'shift'            =>$shift,
                        'tax_ratio'        => $tax,
                        'service_ratio'    => $service_ratio,
                        'delivery'         => $price_location,
                        'discount_tax_service' =>$discount_tax_service,

                    ]);
                    if ($data) {
                        return response()->json(['status' => true,'order'=>$order , 'delivery'=>$price_location]);
                    }
                }

            }else{
                $data = Orders_d::limit(1)->where('branch_id', Auth::user()->branch_id)
                    ->where('order_id', $request->order_id)
                    ->update([
                    'customer_id'      => $request->customer_id,
                    'customer_name'    => $request->customer,
                    'location'         => $request->location,
                    'delivery'         => $price_location,
                ]);
            }
            if ($data) {
                return response()->json(['status' => true]);
            }
        }
    }
}

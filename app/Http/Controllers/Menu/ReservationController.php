<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function save_reservation(Request $request)
    {
        $user_id = Auth::user()->id;
//        if(Table::where(['table_id'=>$request->table_id,'user_id'=>$user_id])->count() == 0){
//            return response()->json(['msg'=>'No operations can be performed on this table']);
//        }
        $branch  = Auth::user()->branch_id;
        $user    = Auth::user()->name;
        $user_id = Auth::user()->id;
        $from    = 0;   // true = 0 ; false = 1;
        $to      = 0;
        $time_final = $request->time_to;
        if(empty($request->time_to))
        {
            $time_final = null;
        }
        $res_day = Reservation::where(['branch_id'=>$branch,'table_id'=>$request->table_id,'date'=>$request->date])
            ->get();

        foreach($res_day as $res){
            // check in time befor time-from
            if($request->time_from < $res->time_from){

                if($time_final == null){
                    $from = 1;
                }
                if($request->time_to < $res->time_from){
                }else{
                    $from = 1;
                }
            }elseif($request->time_from < $res->time_from){
                $from = 1;
            }

            //check in time after time-to
            if($res->time_to == null){
                $to = 1;
            }
            if($request->time_from > $res->time_to){
            }else{
                $to = 1;
            }
        }

        if($from == 0 || $to == 0){
            $create = Reservation::create([
                'branch_id'     =>$branch,
                'user_id'       =>$user_id,
                'user'          =>$user,
                'table_id'      =>$request->table_id,
                'customer'      =>$request->userName,
                'phone'         =>$request->phone_number,
                'cash'          =>$request->cash,
                'date'          =>$request->date,
                'time_from'     =>$request->time_from,
                'time_to'       =>$time_final,
            ]);
            if($create)
            {
                return response()->json(['status'=>'true']);
            }
        }else{
            return response()->json(['erroe'=>'this is time reservied']);
        }



        // if($from == '1' || $to == '1'){

        // }else{
        //     return response()->json(['error'=>'the time is reserved']);
        // }

    }
}

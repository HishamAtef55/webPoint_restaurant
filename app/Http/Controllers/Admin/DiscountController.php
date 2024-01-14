<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use App\Models\Branch;
use App\Models\discounts;
use App\Models\Sub_group;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');

    }
    ######################################### Start Create New Discount ########################
    public function save_discount(DiscountRequest $request)
    {
        $data = discounts::create(
            [
                'branch_id'          =>$request->branch,
                'name'               => $request->name,
                'type'               => $request->discount_type,
                'value'              => $request->value,
            ]
        );
        if($data)
        {
            Permission::create(['name' => $request->name . '-discount','type'=>'pos']);
            return response()->json(
                [
                    'status' => true,
                ]
            );
        }
    }
######################################### End Create New Discount ########################



######################################### Start Search Discount ########################

    public function search_discount(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');

            $data = discounts::with('Branch')
                ->where('branch_id',$request->ID)
                ->where('name', 'LIKE', '%' . $query . "%")
                ->get();
            return response()->json($data);

        }
    }
    ######################################### End Search Discount ########################



    ######################################### Start View Update Discount ########################
    public function view_update_discount()
    {
        $branchs = Branch::get()->all();
        return view('control.update_discount',compact('branchs'));
    }
    ######################################### End View Update Discount ########################


    ######################################### Start  Update Discount ########################
    function action(Request $request)
    {
        if ($request->ajax()) {
            if ($request->action == 'edit')
            {
                $pass_data = "type".$request->id ;
                $data = array(
                    'name'               => $request->name,
                    'value'              => $request->value,
                    'type'               => $request->$pass_data,
                );
                discounts::where('id', $request->id)
                    ->update($data);

                return response()->json($request);
            }

            if ($request->action == 'delete') {
                discounts::where('id', $request->id)->delete();
            }
            return Response()->json($request);

        }
    }
    ######################################### End Update Discount ########################


}

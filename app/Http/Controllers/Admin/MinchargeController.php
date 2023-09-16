<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Hole;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MinchargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');

    }
    public function view_mincharge()
    {
        $branchs = Branch::get()->all();

        return view('control.mincharge', compact('branchs'));
    }

    public function Get_mincharge(Request $request)
    {
        $data = Hole::where('branch_id', $request->branch)
            ->get();
        return response()->json($data);
    }


    public function Save_all_min(Request $request)
    {
        foreach ($request->holearray as $array) {
            $data = Hole::where(['branch_id'=>$request->branch,'number_holes'=>$array["id"]])
                ->update([
                    'min_charge' => $request->min_charge,
                ]);
                Table::where(['branch_id'=>$request->branch , 'hole'=>$array["id"]])
                ->update([
                    'min_charge' => $request->min_charge,
                ]);
        }
        return response()->json([
            'status' => true,
        ]);
    }


    public function Save_one_min(Request $request)
    {
        if ($request->action == 'edit') {
            $data = Hole::where(['branch_id'=>$request->branch , 'number_holes'=>$request->id])
                ->update([
                    'min_charge' => $request->min_charge,
                ]);
            Table::where(['branch_id'=>$request->branch , 'hole'=>$request->id])
            ->update([
                'min_charge' => $request->min_charge,
            ]);
        }
        if ($data) {
            return response()->json($request);
        }
    }

    public function change_charge(Request $request)
    {
        $branch = Auth::user()->branch_id;
        if (isset($request->guest)) {
            $data = Table::where('branch_id', $branch)
                ->where('number_table', $request->table)
                ->update([
                    'guest' => $request->guest
                ]);
        } else {
            $data = Table::where('branch_id', $branch)
                ->where('number_table', $request->table)
                ->update([
                    'min_charge' => $request->min_charge
                ]);
        }
        if ($data) {
            return response()->json([
                'status' => true,
            ]);
        }
    }

}

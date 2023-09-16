<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Stores;
use App\Models\transfersDetails;
use Illuminate\Http\Request;

class TransferReportController extends Controller
{
    public function index(){
        $stores = Stores::get()->all();
        $branches = Branch::get()->all();
        return view('stock.reports.transfer',compact('stores','branches'));
    }

    public function report(Request $request){
        $branch = $request->branch;
        $fromSection = $request->fromSection;
        $toSection = $request->toSection;
        $fromStore = $request->fromStore;
        $toStore = $request->toStore;
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $transfers = [];
        if($request->type == "section"){
            $transfers = transfersDetails::with('main','main.branch','main.to_section','main.from_section')->whereHas('main',function ($query) use($branch,$fromSection,$toSection,$dateFrom,$dateTo){
                $query->whereBetween('date',[$dateFrom , $dateTo])->whereBranchId($branch)->where(['type'=>'section']);
                if($fromSection != "all"){$query->whereFrom($fromSection);}
                if($toSection != "all"){$query->whereTo($toSection);}
            })->get();
        }elseif ($request->type == "store"){
            $transfers = transfersDetails::with('main','main.branch','main.to_store','main.from_store')->whereHas('main',function ($query) use($branch,$fromStore,$toStore,$dateFrom,$dateTo){
                $query->whereBetween('date',[$dateFrom , $dateTo])->where(['type'=>'store']);
                if($fromStore != "all"){$query->whereFrom($fromStore);}
                if($toStore != "all"){$query->whereTo($toStore);}
            })->get();
        }
        return response()->json([
            'status'=>true,
            'transfers'=>$transfers
        ]);
    }
}

<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\exchangeDetails;
use App\Models\exchangeMain;
use App\Models\Stores;
use Illuminate\Http\Request;

class ExchangesReportController extends Controller
{
    public function index(){
        $stores = Stores::get()->all();
        $branches = Branch::get()->all();
        return view('stock.reports.exchange',compact('stores','branches'));
    }

    public function report(Request $request){
        $from = $request->dateFrom;
        $to = $request->dateTo;
        $branch = $request->branch;
        $store = $request->stores;
        $section = $request->sections;
        $exchanges = exchangeDetails::with('main','main.section')->whereHas('main',function ($query) use($from,$to,$branch,$section,$store){
            $query->whereBetween('date',[$from , $to])->where(['store_id'=>$store,'branch_id'=>$branch]);
            if($section != "all"){
                $query->where(['section_id'=>$section]);
            }
        })->get();
        return response()->json([
            'status'=>true,
            'exchanges'=>$exchanges
        ]);
    }
}

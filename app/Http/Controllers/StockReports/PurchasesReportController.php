<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\sectionPurchasesDetails;
use App\Models\storePurchasesDetails;
use App\Models\Stores;
use App\Models\Suppliers;
use DB;
use Illuminate\Http\Request;

class PurchasesReportController extends Controller
{
    public function index(){
        $stores = Stores::get()->all();
        $branches = Branch::get()->all();
        $suppliers = Suppliers::get()->all();
        return view('stock.reports.purchases',compact('stores','branches','suppliers'));
    }

    public function report(Request $request){
        $supplier = $request->supplier;
        $branch   = $request->branch;
        $dateFrom = $request->dateFrom;
        $dateTo   = $request->dateTo;
        $section  = $request->section;
        $store    = $request->store;
        $purchases= [];
        if($request->reportType == "details"){
            if($request->type == "store"){
                $purchases = storePurchasesDetails::with('main','main.store','main.supplier')->whereHas('main',function ($query) use($supplier,$dateTo,$dateFrom,$store){
                    $query->whereBetween('date',[$dateFrom , $dateTo]);
                    if($supplier != "all"){$query->whereSupplier($supplier);}
                    if($store != "all"){$query->whereStoreId($store);}
                })->get();
            }elseif ($request->type == "section"){
                $purchases = sectionPurchasesDetails::with('main','main.section','main.supplier')->whereHas('main',function ($query) use($supplier,$dateFrom,$dateTo,$branch,$section){
                    $query->whereBetween('date',[$dateFrom , $dateTo])->whereBranchId($branch);
                    if($supplier != "all"){$query->whereSupplier($supplier);}
                    if($section != "all"){$query->whereSectionId($section);}
                })->get();
            }
        }elseif($request->reportType == "total"){
            if($request->type == "store"){
                $purchases = storePurchasesDetails::with('main','main.store','main.supplier')->whereHas('main',function ($query) use($supplier,$dateTo,$dateFrom,$store){
                    $query->whereBetween('date',[$dateFrom , $dateTo]);
                    if($supplier != "all"){$query->whereSupplier($supplier);}
                    if($store != "all"){$query->whereStoreId($store);}
                })->get();
            }elseif ($request->type == "section"){
                $purchases = sectionPurchasesDetails::with('main','main.section','main.supplier')->whereHas('main',function ($query) use($supplier,$dateFrom,$dateTo,$branch,$section){
                    $query->whereBetween('date',[$dateFrom , $dateTo])->whereBranchId($branch);
                    if($supplier != "all"){$query->whereSupplier($supplier);}
                    if($section != "all"){$query->whereSectionId($section);}
                })->get();
            }
        }

        return response()->json([
            'status'=>true,
            'purchases'=>$purchases
        ]);
    }
}

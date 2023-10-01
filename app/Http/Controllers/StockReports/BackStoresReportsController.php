<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackStoresReportsController extends Controller
{
    public function index(){
        return view("stock.reports.backStores");
    }

    public function report(Request $request){

    }
}

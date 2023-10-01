<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HalkItemReportsController extends Controller
{
    public function index(){
        return view("stock.reports.halkItem");
    }

    public function report(Request $request){

    }
}

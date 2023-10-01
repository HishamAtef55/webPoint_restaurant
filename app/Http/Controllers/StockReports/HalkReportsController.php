<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HalkReportsController extends Controller
{
    public function index(){
        return view("stock.reports.halk");
    }

    public function report(Request $request){

    }
}

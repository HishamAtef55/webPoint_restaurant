<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufacturingReportsController extends Controller
{
    public function index(){
        return view("stock.reports.manufacturing");
    }

    public function report(Request $request){

    }
}

<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuppliersReportsController extends Controller
{
    public function index(){
        return view("stock.reports.suppliers");
    }

    public function report(Request $request){

    }
}

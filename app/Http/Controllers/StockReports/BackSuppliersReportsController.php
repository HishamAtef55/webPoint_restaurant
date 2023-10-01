<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackSuppliersReportsController extends Controller
{
    public function index(){
        return view("stock.reports.backSuppliers");
    }

    public function report(Request $request){

    }
}

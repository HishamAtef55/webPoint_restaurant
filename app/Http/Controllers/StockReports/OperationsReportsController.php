<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OperationsReportsController extends Controller
{
    public function index(){
        return view("stock.reports.operations");
    }

    public function report(Request $request){

    }
}

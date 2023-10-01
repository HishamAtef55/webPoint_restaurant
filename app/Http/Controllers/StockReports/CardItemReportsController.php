<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardItemReportsController extends Controller
{
    public function index(){
        return view("stock.reports.cardItem");
    }

    public function report(Request $request){

    }
}

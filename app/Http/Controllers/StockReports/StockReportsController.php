<?php

namespace App\Http\Controllers\StockReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockReportsController extends Controller
{
    public function store_balance (){
        return view('stock.reports.store_balance');
    }
}

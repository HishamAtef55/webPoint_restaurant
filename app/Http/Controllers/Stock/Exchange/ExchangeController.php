<?php

namespace App\Http\Controllers\Stock\Exchange;

use App\Models\Branch;
use App\Models\Stock\Store;
use App\Http\Controllers\Controller;
use App\Models\Stock\Exchange;

class ExchangeController extends Controller
{
    public function index()
    {
        $lastExchangeNr = Exchange::latest()->first()?->id + 1 ?? 1;
        $orders = Exchange::get();
        $stores = Store::get();
        $branches = Branch::get();
        return view('stock.Exchange.index', compact('lastExchangeNr', 'stores', 'branches','orders'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Details;
use Illuminate\Http\Request;

class ExportDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');

    }
    public function save(Request $request)
    {
        $data = Details::find($request->details);
        //$data->Items()->syncWithoutDetaching($request->services);
        return $data -> Items()->barcode;
    }
}

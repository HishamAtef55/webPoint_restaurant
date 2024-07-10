<?php

namespace App\Http\Controllers\Stock;

use App\Models\Stores;
use App\Models\material;
use App\Models\storeCost;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\storage_capacity;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Stores::get();
        return view('stock.Stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lastStoreNr = Stores::latest()->first()->id ?? 1;
        return view('stock.Stores.create', compact('lastStoreNr'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        StoreRequest $request
    ): RedirectResponse {
        $store = Stores::create($request->validated());

        // $permission = "stocks-" . $request->name;
        // Permission::create([
        //     'name' => $permission,
        //     'type' => 'stock'
        // ]);

        if ($store) {
            $storageTypes = $request->input('type');
            $units = $request->input('unit');
            $capacities = $request->input('capacity');
            foreach ($storageTypes as $key => $type) {
                $unit = $units[$key];
                $capacity = $capacities[$key];
                storage_capacity::create([
                    'store' => $store->id,
                    'type' => $type,
                    'unit' => $unit,
                    'capacity' => $capacity,
                ]);
            }

            foreach (material::select(['code', 'name', 'unit'])->get() as $material) {
                storeCost::create([
                    'store_id' => $store->id,
                    'code' => $material->code,
                    'material' => $material->name,
                    'unit' => $material->unit,
                ]);
            }
        }
        return redirect()->route('stock.stores.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

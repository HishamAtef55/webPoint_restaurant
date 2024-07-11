<?php

namespace App\Http\Controllers\Stock;

use App\Models\Stores;
use App\Models\material;
use App\Models\storeCost;
use Illuminate\Http\Request;
use App\Models\storage_capacity;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lastStoreNr = Stores::latest()->first()->id ?? 1;
        $stores = Stores::get();
        return view('stock.Stores.index', compact([
            'stores',
            'lastStoreNr'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        StoreRequest $request
    ): StoreResource {
        $store = Stores::create($request->validated());
        if ($store) {
            foreach ($request->storages as $storage) {
                storage_capacity::create([
                    'store_id' => $store->id,
                    'type' => $storage['type'],
                    'unit' => $storage['unit'],
                    'capacity' => $storage['capacity'],
                ]);
            }
        }
        $stores = Stores::get();
        return StoreResource::make($store)
            ->additional([
                'stores' => StoreResource::collection($stores),
                'message' => "تم إانشاء المخزن بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        Stores $store
    ): StoreResource {
        return StoreResource::make($store)
            ->additional([
                'message' => null,
                'status' => Response::HTTP_OK
            ]);
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
    public function destroy(
        Stores $store
    ): JsonResponse {
        if ($store->delete()) {
            return response()->json([
                'message' => 'تم حذف المخزن',
                'status' => Response::HTTP_OK
            ]);
        }
    }
}

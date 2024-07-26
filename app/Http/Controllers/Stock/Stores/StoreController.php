<?php

namespace App\Http\Controllers\Stock\Stores;

use App\Enums\Unit;
use App\Enums\StorageType;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\StoreResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Stock\Stores\StoreRequest;
use App\Http\Requests\Stock\Stores\UpdateRequest;
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
        $lastStoreNr = Store::latest()->first()?->id + 1 ?? 1;
        $stores = Store::paginate(5);
        $units = Unit::values();
        $storageTypes = StorageType::values();
        return view('stock.Stores.index', compact([
            'stores',
            'lastStoreNr',
            'units',
            'storageTypes'
        ]));
    }

    /**
     * store
     * @param StoreRequest $request
     * @return StoreResource
     */
    public function store(
        StoreRequest $request
    ): StoreResource {
        $store = Store::create($request->validated());
        if ($store && $request->storages) {
            foreach ($request->storages as $storage) {
                $store->storageCapacity()->create([
                    'type' => $storage['type'],
                    'unit' => $storage['unit'],
                    'capacity' => $storage['capacity'],
                ]);
            }
        }
        return StoreResource::make($store)
            ->additional([
                'message' => "تم إنشاء المخزن بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * show
     * @param  Store $store
     * @return StoreResource
     */
    public function show(
        Store $store
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
     * @param  UpdateRequest $request,
     * @param  Store $store
     * @return StoreResource
     */
    public function update(
        UpdateRequest $request,
        Store $store
    ) {
        $storeUpdated = $store->update($request->validated());
        if ($storeUpdated && $request->storages) {
            $store->storageCapacity()->delete();
            foreach ($request->storages as $storage) {
                $store->storageCapacity()->create([
                    'type' => $storage['type'],
                    'unit' => $storage['unit'],
                    'capacity' => $storage['capacity'],
                ]);
            }
        }
        return StoreResource::make($store)
            ->additional([
                'message' => "تم تعديل المخزن بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * destroy.
     * @param Store $store
     * @return JsonResponse
     */
    public function destroy(
        Store $store
    ): JsonResponse {
        if ($store->hasSection()) {
            return response()->json([
                'message' => 'لايمكن حذف المخزن لانة يحتوى على أقسام',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        }
        if ($store->delete()) {
            return response()->json([
                'message' => 'تم حذف المخزن',
                'status' => Response::HTTP_OK
            ]);
        }
    }
}

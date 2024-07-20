<?php

namespace App\Http\Controllers\Stock\Suppliers;


use App\Models\Stock\Supplier;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\Suppliers\UpdateSupplierRequest;
use App\Http\Resources\Stock\SupplierResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Stock\Suppliers\StoreSupplierRequest;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lastSupplierNr = Supplier::latest()->first()?->id + 1 ?? 1;
        $supplires = Supplier::paginate(5);
        return view('stock.Suppliers.index', compact('lastSupplierNr', 'supplires'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSupplierRequest $request
     * @return SupplierResource
     */
    public function store(
        StoreSupplierRequest $request
    ): SupplierResource {
        return SupplierResource::make(
            Supplier::create($request->validated())
        )->additional([
            'message' => "تم إنشاء المورد بنجاح",
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * show
     * @param Supplier $supplier
     * @return StoreResource
     */
    public function show(
        Supplier $supplier
    ): SupplierResource {
        return SupplierResource::make($supplier)
            ->additional([
                'message' => null,
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * update.
     *
     * @param  UpdateSupplierRequest $request
     * @param  Supplier $supplier
     * @return SupplierResource
     */
    public function update(
        UpdateSupplierRequest $request,
        Supplier $supplier
    ): SupplierResource {
        if ($supplier->update($request->validated())) {
            return SupplierResource::make($supplier)
                ->additional([
                    'message' => 'تم تعديل بيانات المورد',
                    'status' => Response::HTTP_OK
                ]);
        }
    }

    /**
     * destroy.
     * @param Supplier $supplier
     * @return JsonResponse
     */
    public function destroy(
        Supplier $supplier
    ): JsonResponse {
        if ($supplier->delete()) {
            return response()->json([
                'message' => 'تم حذف المورد',
                'status' => Response::HTTP_OK
            ]);
        }
    }
}

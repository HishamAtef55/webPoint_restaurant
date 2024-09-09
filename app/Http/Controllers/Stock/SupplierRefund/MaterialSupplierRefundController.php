<?php

namespace App\Http\Controllers\Stock\SupplierRefund;

use App\Enums\Unit;

use App\Models\Branch;
use App\Invoices\Invoice;
use Illuminate\View\View;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Models\Stock\Material;
use App\Models\Stock\Supplier;
use App\Models\Stock\Purchases;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Stock\MaterialSupplierRefund;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\PurchasesResource;
use App\Services\MaterialSupplierRefundService;
use App\Http\Resources\Stock\SupplierRefundResource;
use App\Http\Requests\Stock\Purchases\StorePurchasesRequest;
use App\Http\Requests\Stock\Purchases\UpdatePurchasesRequest;
use App\Http\Requests\Stock\SupplierRefund\StoreSupplierRefundRequest;
use App\Http\Requests\Stock\SupplierRefund\UpdateSupplierRefundRequest;

class MaterialSupplierRefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $lastRefundNr = MaterialSupplierRefund::latest()->first()?->id + 1 ?? 1;
        $refunds  = MaterialSupplierRefund::get();
        $stores    = Store::has('balance')->get();
        $branches = Branch::get();
        $suppliers = Supplier::has('purchases')->get();
        $materials = Material::with('details')->get();
        $materials->map(function ($material) {
            $material->details =   $material->details()->latest('created_at')->first();
            $material->unit = Unit::view($material->unit);
        });
        return view('stock.SupplierRefund.index', compact('lastRefundNr', 'stores', 'branches', 'suppliers', 'refunds', 'materials'));
    }

    /**
     * store
     *
     * @param  StoreSupplierRefundRequest  $request
     * @param  MaterialSupplierRefundService $service
     * @return JsonResponse
     */
    public function store(
        StoreSupplierRefundRequest  $request,
        MaterialSupplierRefundService $service
    ): JsonResponse {
        if ($service->create($request->validated())) {
            return response()->json([
                'message' => 'تم إنشاء اذن مرتجع بنجاح',
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'message' => 'حدث خطأ',
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * show
     *
     * @param  MaterialSupplierRefund  $refund
     * @return SupplierRefundResource
     */
    public function show(
        MaterialSupplierRefund  $refund
    ): SupplierRefundResource {
        return SupplierRefundResource::make(
            $refund
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * update
     *
     * @param MaterialSupplierRefund  $refund
     * @param  UpdateSupplierRefundRequest  $request
     * @param  MaterialSupplierRefundService $service
     */
    public function update(
        MaterialSupplierRefund  $refund,
        UpdateSupplierRefundRequest  $request,
        MaterialSupplierRefundService $service
    ): PurchasesResource|JsonResponse {
        try {
            if ($service->update($request->validated(), $refund)) {
                return PurchasesResource::make(
                    $refund
                )->additional([
                    'message' => 'تم تعديل اذن مرتجع بنجاح',
                    'status' => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'حدث خطأ',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * destroy
     *
     * @param MaterialSupplierRefund  $refund,
     * @param  Request  $request
     * @param MaterialSupplierRefundService $service
     * @return PurchasesResource|JsonResponse {
     */
    public function destroy(
        MaterialSupplierRefund  $refund,
        Request $request,
        MaterialSupplierRefundService $service
    ): PurchasesResource|JsonResponse {
        try {
            if ($service->delete($refund, $request->details_id)) {
                return response()->json([
                    'message' => "تم حذف الخامة",
                    'status' => Response::HTTP_OK
                ]);
            }
            return response()->json([
                'message' => 'لا يمكن حذف خامة على الاقل',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

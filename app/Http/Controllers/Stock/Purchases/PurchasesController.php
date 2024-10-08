<?php

namespace App\Http\Controllers\Stock\Purchases;

use App\Enums\Unit;

use App\Models\Branch;
use Illuminate\View\View;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Models\Stock\Material;
use App\Models\Stock\Supplier;
use App\Models\Stock\Purchases;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\PurchasesResource;
use App\Http\Requests\Stock\Purchases\StorePurchasesRequest;
use App\Http\Requests\Stock\Purchases\UpdatePurchasesRequest;
use App\Invoices\Invoice;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $lastPurchaseslNr = Purchases::latest()->first()?->id + 1 ?? 1;
        $invoices  = Purchases::get();
        $stores    = Store::get();
        $branches = Branch::get();
        $suppliers = Supplier::get();
        $materials = Material::with('details')->get();
        $materials->map(function ($material) {
            $material->details =   $material->details()->latest('created_at')->first();
            $material->unit = Unit::view($material->unit);
        });
        return view('stock.Purchases.index', compact('lastPurchaseslNr', 'stores', 'branches', 'suppliers', 'materials', 'invoices'));
    }

    /**
     * store
     *
     * @param  StorePurchasesRequest  $request
     * @param  Invoice $invoice
     * @return JsonResponse
     */
    public function store(
        StorePurchasesRequest  $request,
        Invoice $invoice
    ): JsonResponse {
        if ($invoice->create($request->validated())) {
            return response()->json([
                'message' => 'تم إنشاء الفاتورة بنجاح',
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
     * @param  Purchases  $purchase
     * @return PurchasesResource
     */
    public function show(
        Purchases  $purchase
    ): PurchasesResource {
        return PurchasesResource::make(
            $purchase
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * update
     *
     * @param  UpdatePurchasesRequest  $request
     * @param  Purchases  $purchase
     * @return PurchasesResource|JsonResponse
     */
    public function update(
        Purchases  $purchase,
        UpdatePurchasesRequest  $request,
        Invoice $invoice
    ): PurchasesResource|JsonResponse {

        try {
            if ($invoice->update($request->validated(), $purchase)) {
                return PurchasesResource::make(
                    $purchase
                )->additional([
                    'message' => 'تم تعديل الفاتورة بنجاح',
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
     * @param  Purchases  $purchase
     * @param  Request  $request
     * @param  Invoice  $invoice
     * @return PurchasesResource|JsonResponse
     */
    public function destroy(
        Purchases  $purchase,
        Request $request,
        Invoice $invoice
    ): PurchasesResource|JsonResponse {
        try {
            if ($invoice->delete($purchase, $request->details_id)) {
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

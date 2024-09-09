<?php

namespace App\Http\Controllers\Stock\StoreRefund;


use App\Models\Branch;
use Illuminate\View\View;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Stock\MaterialStoreRefund;
use App\Services\MaterialStoreRefundService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\StoreRefundResource;
use App\Http\Requests\Stock\StoreRefund\StoreRefundRequest;
use App\Http\Requests\Stock\StoreRefund\UpdateRefundRequest;


class MaterialStoreRefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $lastRefundNr = MaterialStoreRefund::latest()->first()?->id + 1 ?? 1;
        $refunds  = MaterialStoreRefund::get();
        $stores   = Store::get();
        $branches = Branch::get();
        return view('stock.StoreRefund.index', compact('lastRefundNr', 'stores', 'branches', 'refunds'));
    }

    /**
     * store
     *
     * @param  StoreRefundRequest  $request
     * @param  MaterialStoreRefundService $service
     * @return JsonResponse
     */
    public function store(
        StoreRefundRequest  $request,
        MaterialStoreRefundService $service
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
     * @param  MaterialStoreRefund  $refund
     * @return StoreRefundResource
     */
    public function show(
        MaterialStoreRefund  $refund
    ): StoreRefundResource {
        return StoreRefundResource::make(
            $refund
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * update
     *
     * @param MaterialStoreRefund  $refund
     * @param  UpdateRefundRequest  $request
     * @param  MaterialStoreRefundService $service
     */
    public function update(
        MaterialStoreRefund  $refund,
        UpdateRefundRequest  $request,
        MaterialStoreRefundService $service
    ): StoreRefundResource|JsonResponse {
        try {
            if ($service->update($request->validated(), $refund)) {
                return StoreRefundResource::make(
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
     * @param MaterialStoreRefund  $refund,
     * @param  Request  $request
     * @param MaterialStoreRefundService $service
     * @return StoreRefundResource|JsonResponse {
     */
    public function destroy(
        MaterialStoreRefund  $refund,
        Request $request,
        MaterialStoreRefundService $service
    ): StoreRefundResource|JsonResponse {
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

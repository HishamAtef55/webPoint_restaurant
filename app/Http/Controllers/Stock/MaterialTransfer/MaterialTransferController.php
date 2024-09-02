<?php

namespace App\Http\Controllers\Stock\MaterialTransfer;

use App\Models\Branch;
use App\Models\Stock\Store;
use App\Models\Stock\Section;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Stock\MaterialTransfer;
use App\Services\MaterialTransferService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Stock\MaterialsTransfer\StoreMaterialTransferRequest;
use Illuminate\Http\Request;

class MaterialTransferController extends Controller
{

    /**
     * index
     *
     * @return view
     */
    public function index()
    {
        $lastTransferNr = MaterialTransfer::latest()->first()?->id + 1 ?? 1;
        $transfers = MaterialTransfer::get();
        $branches = Branch::get();
        $stores = Store::get();
        return view('stock.MaterialsTransfer.index', compact('lastTransferNr', 'stores', 'branches', 'transfers'));
    }

    /**
     * store
     *
     * @param  StoreMaterialTransferRequest  $request
     * @param ExchangeService $exchange
     * @return JsonResponse
     */
    public function store(
        StoreMaterialTransferRequest  $request,
        MaterialTransferService $service,
    ): JsonResponse {
        if ($service->store($request->validated())) {
            return response()->json([
                'message' => 'تم إنشاء إذن التحويل بنجاح',
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
     * @param  Exchange  $exchange
     * @return ExchangeResource
     */
    public function show(
        Exchange  $exchange
    ): ExchangeResource {
        return ExchangeResource::make(
            $exchange
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * update
     *
     * @param  Exchange  $exchange
     * @param  UpdateExchangeRequest  $request
     * @param  ExchangeService $service,
     * @return ExchangeResource
     */
    public function update(
        Exchange  $exchange,
        UpdateExchangeRequest  $request,
        ExchangeService $service,
    ): ExchangeResource {

        try {
            if ($service->update($request->validated(), $exchange)) {
                return ExchangeResource::make(
                    $exchange
                )->additional([
                    'message' => 'تم تعديل إذن الصرف بنجاح بنجاح',
                    'status' => Response::HTTP_OK
                ], Response::HTTP_OK);
            }

            return response()->json([
                'message' => 'لا يمكن تعديل إذن الصرف',
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
     * @param  Exchange  $exchange
     * @param  Request $reques
     * @param  ExchangeService $service
     * @return ExchangeResource|JsonResponse
     */
    public function destroy(
        Exchange  $exchange,
        Request $request,
        ExchangeService $service
    ): ExchangeResource|JsonResponse {

        try {
            if ($service->delete($exchange, $request->details_id)) {
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

<?php

namespace App\Http\Controllers\Stock\MaterialTransfer;

use App\Models\Branch;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Stock\MaterialTransfer;
use App\Services\MaterialTransferService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\MaterialTransferResource;
use App\Http\Requests\Stock\MaterialsTransfer\StoreMaterialTransferRequest;
use App\Http\Requests\Stock\MaterialsTransfer\UpdateMaterialTransferRequest;

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
     * @param  MaterialTransfer  $transfer
     * @return MaterialTransferResource
     */
    public function show(
        MaterialTransfer  $transfer
    ): MaterialTransferResource {
        return MaterialTransferResource::make(
            $transfer
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * update
     *
     * @param  MaterialTransfer  $transfer
     * @param  UpdateMaterialTransferRequest  $request
     * @param  MaterialTransferService $service,
     * @return MaterialTransferResource|JsonResponse
     */
    public function update(
        MaterialTransfer  $transfer,
        UpdateMaterialTransferRequest  $request,
        MaterialTransferService $service,
    ): MaterialTransferResource|JsonResponse {
        try {
            if ($service->update($request->validated(), $transfer)) {
                return MaterialTransferResource::make(
                    $transfer
                )->additional([
                    'message' => 'تم تعديل إذن التحويل بنجاح ',
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
     * @param  MaterialTransfer  $transfer
     * @param  Request $reques
     * @param  MaterialTransferService $service
     * @return MaterialTransferResource|JsonResponse
     */
    public function destroy(
        MaterialTransfer  $transfer,
        Request $request,
        MaterialTransferService $service
    ): MaterialTransferResource|JsonResponse {

        try {
            if ($service->delete($transfer, $request->details_id)) {
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

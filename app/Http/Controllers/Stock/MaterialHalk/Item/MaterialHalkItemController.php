<?php

namespace App\Http\Controllers\Stock\MaterialHalk\Item;

use App\Models\Branch;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Models\Stock\MaterialHalk;
use App\Http\Controllers\Controller;
use App\Services\MaterialHalkService;
use App\Models\Stock\MaterialHalkItem;
use App\Services\MaterialHalkItemService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\MaterialHalkResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Resources\Stock\MaterialHalkItemResource;
use App\Http\Requests\Stock\MaterialHalk\StoreMaterialHalkRequest;
use App\Http\Requests\Stock\MaterialHalk\UpdateMaterialHalkRequest;
use App\Http\Requests\Stock\MaterialHalk\Item\StoreMaterialHalkItemRequest;
use App\Http\Requests\Stock\MaterialHalk\Item\UpdateMaterialHalkItemRequest;

class MaterialHalkItemController extends Controller
{

    /**
     * index
     *
     * @return view
     */
    public function index()
    {
        $lastHalkItemNr = MaterialHalkItem::latest()->first()?->id + 1 ?? 1;
        $halksItem = MaterialHalkItem::get();
        $branches = Branch::get();
        return view('stock.MaterialsHalk.Items.index', compact('lastHalkItemNr', 'halksItem', 'branches',));
    }

    /**
     * store
     *
     * @param  StoreMaterialHalkItemRequest  $request
     * @param MaterialHalkService $exchange
     * @return JsnResponse
     */
    public function store(
        StoreMaterialHalkItemRequest  $request,
        MaterialHalkItemService $service,
    ): JsonResponse {

        try {
            if ($service->create($request->validated())) {
                return response()->json([
                    'message' => 'تم إنشاء إذن هالك صنف بنجاح',
                    'status' => Response::HTTP_CREATED
                ], Response::HTTP_CREATED);
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
     * show
     *
     * @param  MaterialHalkItem  $item
     * @return MaterialHalkItemResource
     */
    public function show(
        MaterialHalkItem  $item
    ): MaterialHalkItemResource {
        return MaterialHalkItemResource::make(
            $item
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * update
     *
     * @param  MaterialHalkItem  $item,
     * @param  UpdateMaterialHalkItemRequest  $request
     * @param  MaterialHalkItemService $service,
     * @return MaterialHalkItemResource|JsonResponse
     */
    public function update(
        MaterialHalkItem  $item,
        UpdateMaterialHalkItemRequest  $request,
        MaterialHalkItemService $service,
    ): MaterialHalkItemResource|JsonResponse {

        try {
            if ($service->update($request->validated(), $item)) {
                return MaterialHalkItemResource::make(
                    $item
                )->additional([
                    'message' => 'تم تعديل إذن هالك صنف بنجاح',
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
     * @param  MaterialHalkItem  $item,
     * @param  Request $reques
     * @param  MaterialHalkItemService $service
     * @return MaterialHalkResource|JsonResponse
     */
    public function destroy(
        MaterialHalkItem  $item,
        Request $request,
        MaterialHalkItemService $service,
    ): MaterialHalkResource|JsonResponse {

        try {
            if ($service->delete($item, $request->details_id)) {
                return response()->json([
                    'message' => "تم حذف الصنف",
                    'status' => Response::HTTP_OK
                ]);
            }

            return response()->json([
                'message' => 'لا يمكن حذف صنف على الاقل',
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

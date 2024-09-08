<?php

namespace App\Http\Controllers\Stock\MaterialHalk;

use App\Models\Branch;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Models\Stock\MaterialHalk;
use App\Http\Controllers\Controller;
use App\Services\MaterialHalkService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\MaterialHalkResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Stock\MaterialHalk\StoreMaterialHalkRequest;
use App\Http\Requests\Stock\MaterialHalk\UpdateMaterialHalkRequest;

class MaterialHalkController extends Controller
{

    /**
     * index
     *
     * @return view
     */
    public function index()
    {
        $lastHalkNr = MaterialHalk::latest()->first()?->id + 1 ?? 1;
        $halks = MaterialHalk::get();
        $branches = Branch::get();
        $stores = Store::has('balance')->get();
        return view('stock.MaterialsHalk.index', compact('lastHalkNr', 'stores', 'branches', 'halks'));
    }

    /**
     * store
     *
     * @param  StoreMaterialHalkRequest  $request
     * @param MaterialHalkService $exchange
     * @return JsonResponse
     */
    public function store(
        StoreMaterialHalkRequest  $request,
        MaterialHalkService $service,
    ): JsonResponse {
        if ($service->create($request->validated())) {
            return response()->json([
                'message' => 'تم إنشاء إذن الهالك بنجاح',
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
     * @param   MaterialHalk  $halk
     * @return MaterialHalkResource
     */
    public function show(
        MaterialHalk  $halk
    ): MaterialHalkResource {
        return MaterialHalkResource::make(
            $halk
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }

    /**
     * update
     *
     * @param  MaterialHalk  $halk
     * @param  UpdateMaterialHalkRequest  $request
     * @param  MaterialHalkService $service,
     * @return MaterialHalkResource|JsonResponse
     */
    public function update(
        MaterialHalk  $halk,
        UpdateMaterialHalkRequest  $request,
        MaterialHalkService $service,
    ): MaterialHalkResource|JsonResponse {
        try {
            if ($service->update($request->validated(), $halk)) {
                return MaterialHalkResource::make(
                    $halk
                )->additional([
                    'message' => 'تم تعديل إذن الهالك بنجاح ',
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
     * @param  MaterialHalk  $halk
     * @param  Request $reques
     * @param  MaterialHalkService $service
     * @return MaterialHalkResource|JsonResponse
     */
    public function destroy(
        MaterialHalk  $halk,
        Request $request,
        MaterialHalkService $service,
    ): MaterialHalkResource|JsonResponse {

        try {
            if ($service->delete($halk, $request->details_id)) {
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

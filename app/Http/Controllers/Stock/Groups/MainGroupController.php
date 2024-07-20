<?php

namespace App\Http\Controllers\Stock\Groups;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\StoreResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\StockGroupResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Stock\MainGroups\StoreMainGroupRequest;
use App\Http\Requests\Stock\MainGroups\UpdateMainGroupRequest;

class MainGroupController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        $lastGroupNr = StockGroup::isRoot()->latest()->first()?->id + 1 ?? 1;
        $groups = StockGroup::isRoot()->paginate(5);
        return view('stock.MainGroups.index', compact('lastGroupNr', 'groups'));
    }

    /**
     * store
     * @param StoreMainGroupRequest $request
     * @return StockGroupResource
     */
    public function store(
        StoreMainGroupRequest $request
    ): StockGroupResource {
        return StockGroupResource::make(
            StockGroup::create($request->validated())
        )
            ->additional([
                'message' => "تم إنشاء المجوعة الرئيسية بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * show
     * @param  StockGroup $stockGroup
     * @return StockGroupResource
     */
    public function show(
        StockGroup $stockGroup
    ): StockGroupResource {
        return StockGroupResource::make($stockGroup)
            ->additional([
                'message' => null,
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateMainGroupRequest $request,
     * @param  StockGroup $stockGroup
     * @return StoreResource
     */
    public function update(
        UpdateMainGroupRequest $request,
        StockGroup $stockGroup
    ): StockGroupResource {
        $stockGroup->update($request->validated());
        return StockGroupResource::make($stockGroup)
            ->additional([
                'message' => "تم تعديل المجموعة الرئيسية بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * destroy.
     * @param  MainGroup $maingroup
     * @return JsonResponse
     */
    public function destroy(
        StockGroup $stockGroup
    ): JsonResponse {
        if ($stockGroup->hasChildren()) {
            return response()->json([
                'message' => 'لايمكن حذف المجموعة لانها تحتوى على مجموعات فرعية',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        }
        if ($stockGroup->delete()) {
            return response()->json([
                'message' => 'تم حذف المجموعة الرئيسية بنجاح',
                'status' => Response::HTTP_OK
            ]);
        }
    }
}

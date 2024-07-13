<?php

namespace App\Http\Controllers\Stock;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\StoreResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\MainGroupResource;
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
        $groups = StockGroup::isRoot()->get();
        return view('stock.MainGroups.index', compact('lastGroupNr', 'groups'));
    }

    /**
     * store
     * @param StoreMainGroupRequest $request
     * @return MainGroupResource
     */
    public function store(
        StoreMainGroupRequest $request
    ): MainGroupResource {
        return MainGroupResource::make(
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
     * @return MainGroupResource
     */
    public function show(
        StockGroup $stockGroup
    ): MainGroupResource {
        return MainGroupResource::make($stockGroup)
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
    ): MainGroupResource {
        $stockGroup->update($request->validated());
        return MainGroupResource::make($stockGroup)
            ->additional([
                'message' => "تم تعديل المجموعةالرئيسية بنجاح",
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
        if ($stockGroup->delete()) {
            return response()->json([
                'message' => 'تم حذف المجموعةالرئيسية بنجاح',
                'status' => Response::HTTP_OK
            ]);
        }
    }
}

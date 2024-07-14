<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\StockGroupResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Stock\SubGroups\StoreSubGroupRequest;
use App\Http\Requests\Stock\SubGroups\UpdateSubGroupRequest;

class SubGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lastSubGroupNr = StockGroup::hasParent()->latest()->first()?->id + 1 ?? 1;
        $mainGroups = StockGroup::isRoot()->get();
        $subGroups = StockGroup::hasParent()->orderBy('serial_nr', 'asc')->paginate(5);
        return view('stock.SubGroups.index', compact('lastSubGroupNr', 'mainGroups', 'subGroups'));
    }

    /**
     * store
     * @param StoreSubGroupRequest $request
     * @return StockGroupResource
     */
    public function store(
        StoreSubGroupRequest $request
    ): StockGroupResource {
        return StockGroupResource::make(
            StockGroup::create($request->validated())
        )
            ->additional([
                'message' => "تم إنشاء المجوعة الفرعية بنجاح",
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
     * @param  UpdateSubGroupRequest $request,
     * @param  StockGroup $stockGroup
     * @return StockGroupResource
     */
    public function update(
        UpdateSubGroupRequest $request,
        StockGroup $stockGroup
    ): StockGroupResource {
        $stockGroup->update($request->validated());
        return StockGroupResource::make($stockGroup)
            ->additional([
                'message' => "تم تعديل المجموعةالفرعية بنجاح",
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
                'message' => 'تم حذف المجموعةالفرعية بنجاح',
                'status' => Response::HTTP_OK
            ]);
        }
    }
}

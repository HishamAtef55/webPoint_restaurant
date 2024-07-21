<?php

namespace App\Http\Controllers\Stock\Groups;

use Illuminate\Http\Request;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Stock\StockGroupResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Stock\SubGroups\FilterSubGroup;
use App\Http\Requests\Stock\SubGroups\StoreSubGroupRequest;
use App\Http\Requests\Stock\SubGroups\FilterSubGroupRequest;
use App\Http\Requests\Stock\SubGroups\UpdateSubGroupRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

        $stockGroup = StockGroup::create($request->validated());
        $stockGroup->serial_nr = $request->setSerialNr($request->parent_id);
        $stockGroup->save();
        return StockGroupResource::make(
            $stockGroup
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
        $stockGroup->serial_nr = $request->updateSerialNr();
        $stockGroup->update($request->validated());
        $stockGroup->save();
        return StockGroupResource::make($stockGroup)
            ->additional([
                'serial_nr' => $request->updateSerialNr(),
                'message' => "تم تعديل المجموعة الفرعية بنجاح",
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
        if ($stockGroup->hasMaterials()) {
            return response()->json([
                'message' => 'لايمكن حذف المجموعة الفرعية لانها تحتوى على خامات',
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        }
        if ($stockGroup->delete()) {
            return response()->json([
                'message' => 'تم حذف المجموعة الفرعية بنجاح',
                'status' => Response::HTTP_OK
            ]);
        }
    }

    /**
     * filter.
     * @param  FilterSubGroupRequest $request
     * @return JsonResponse
     */
    public function filter(
        FilterSubGroupRequest $request,
    ): JsonResponse {
        $groups = StockGroup::where('parent_id', $request->parent_id)
            ->orderBy('serial_nr', 'asc')->paginate(5);
        // ->get();

        return response()->json([
            'data' => $groups,
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}

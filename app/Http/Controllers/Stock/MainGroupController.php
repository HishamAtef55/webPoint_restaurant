<?php

namespace App\Http\Controllers\Stock;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Stock\MainGroup;
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
        $lastGroupNr = MainGroup::latest()->first()?->id + 1 ?? 1;
        $groups = MainGroup::get();
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
            MainGroup::create($request->validated())
        )
            ->additional([
                'message' => "تم إنشاء المجوعة الرئيسية بنجاح بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * show
     * @param  MainGroup $maingroup
     * @return MainGroupResource
     */
    public function show(
        MainGroup $maingroup
    ): MainGroupResource {
        return MainGroupResource::make($maingroup)
            ->additional([
                'message' => null,
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateMainGroupRequest $request,
     * @param  MainGroup $maingroup
     * @return StoreResource
     */
    public function update(
        UpdateMainGroupRequest $request,
        MainGroup $maingroup
    ): MainGroupResource {
        $maingroup->update($request->validated());
        return MainGroupResource::make($maingroup)
            ->additional([
                'message' => "تم تعديل المجموعة الرئسية بنجا بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * destroy.
     * @param  MainGroup $maingroup
     * @return JsonResponse
     */
    public function destroy(
        MainGroup $maingroup
    ): JsonResponse {
        if ($maingroup->delete()) {
            return response()->json([
                'message' => 'تم حذف المجموعة الرئيسية بنجاح',
                'status' => Response::HTTP_OK
            ]);
        }
    }
}

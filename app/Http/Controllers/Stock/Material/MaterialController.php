<?php

namespace App\Http\Controllers\Stock\Material;

use App\Enums\Unit;
use App\Models\Units;
use App\Models\Branch;
use App\Enums\StorageType;
use App\Enums\MaterialType;
use Illuminate\Http\Request;
use App\Models\Stock\material;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\MaterialResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Stock\Material\StoreMaterialRequest;
use Illuminate\Http\JsonResponse;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainGroup = StockGroup::isRoot()->get();
        $units = Unit::values();
        $storageTypes = StorageType::values();
        $branchs = Branch::get();
        $materialTypes = MaterialType::values();
        $lastMaterialNr = Material::latest()->first()?->id + 1 ?? 1;
        $materials = material::with('group', 'branch')->orderBy('id', 'DESC')->paginate(5);
        return view('stock.Material.index', compact('mainGroup', 'units', 'storageTypes', 'branchs', 'materials', 'lastMaterialNr', 'materialTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreMaterialRequest  $request
     * @return MaterialResource
     */
    public function store(
        StoreMaterialRequest $request
    ): MaterialResource {

        $validatedData = $request->validated();
        $sectionIds = array_map(function ($item) {
            return $item['id'];
        }, $validatedData['sectionIds']);
        $material = Material::create($validatedData);
        $material->sections()->attach($sectionIds);
        return MaterialResource::make($material)
            ->additional([
                'message' => "تم إنشاء الخامة بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Material  $material
     * @return MaterialResource
     */
    public function show(
        Material $material
    ): MaterialResource {
        return MaterialResource::make(
            $material
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Material  $material
     * @return JsonResponse
     */
    public function destroy(
        Material $material
    ): JsonResponse {
        if ($material->delete()) {
            return response()->json([
                'message' => 'تم حذف الخامة',
                'status' => Response::HTTP_OK
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Stock\Material;

use App\Enums\Unit;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\Stock\Material;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use App\Models\Stock\MaterialRecipe;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Resources\Stock\MaterialRecipeResource;
use App\Http\Requests\Stock\Material\StoreMaterialRequest;
use App\Http\Requests\Stock\Material\Recipe\StoreRecipeRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Stock\Material\Recipe\RepeatRecipeRequest;
use App\Models\Stock\MaterialComponent;

class MaterialRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branchs = Branch::get();
        $materials = Material::get();
        $materials->map(function ($material) {
            $material->unit = Unit::view($material->unit);
        });
        return view('stock.Material.Recipe.index', compact('branchs', 'materials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * store
     *
     * @param  StoreRecipeRequest $request
     * @return JsonResponse
     */
    public function store(
        StoreRecipeRequest $request
    ): JsonResponse {
        foreach ($request->validated()['components'] as $component) {
            MaterialRecipe::updateOrCreate(
                [
                    'material_id' => $request->validated()['material_id'],
                    'material_recipe_id' => $component['code']
                ],
                [
                    'quantity' => $component['quantity'],
                    'price' => $component['price'] * 100,
                    'unit' => $component['unit']
                ]

            );
            MaterialComponent::updateOrCreate(
                [
                    'material_id' => $request->validated()['material_id'],
                ],
                [
                    'qty' => $request->validated()['component_qty'],
                ]
                );
        }

        return response()->json([
            'message' => "تم إنشاء مكونات الخامة بنجاح",
            'status' => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
     * destroy.
     *
     * @param   MaterialRecipe $materialRecipe
     * @return JsonResponse
     */
    public function destroy(
        MaterialRecipe $materialRecipe
    ): JsonResponse {
        if ($materialRecipe->delete()) {
            return response()->json([
                'message' => 'تم حذف مكون الخامة بنجاح',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    /**
     * filter
     *
     * @return AnonymousResourceCollection
     */
    public function filter(Request $request): AnonymousResourceCollection
    {
        $materialId = $request->query('material_id');
        return MaterialRecipeResource::collection(
            MaterialRecipe::byMaterialId($materialId)->get()
        )->additional([
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * repeat.
     *
     * @param  RepeatRecipeRequest $request
     * @return JsonResponse
     */
    public function repeat(
        RepeatRecipeRequest $request,
    ): JsonResponse {
        foreach ($request->validated()['material_id'] as $materialId) {
            foreach ($request->validated()['components'] as $component) {
                MaterialRecipe::create(
                    [
                        'material_id' => $materialId,
                        'material_recipe_id' => $component['code'],
                        'quantity' => $component['quantity'],
                        'price' => $component['price'] * 100,
                        'unit' => $component['unit']
                    ]

                );
            }
        }
        return response()->json([
            'message' => 'تم تكرار الخامة بنجاح',
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}

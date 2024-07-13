<?php

namespace App\Http\Controllers\Stock;

use App\Models\Group;
use App\Models\Branch;
use Illuminate\View\View;
use App\Models\Stock\Store;
use Illuminate\Http\Request;
use App\Models\Stock\Section;
use App\Http\Controllers\Controller;
use App\Http\Resources\Stock\GroupResource;
use App\Http\Resources\Stock\SectionResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Stock\Groups\GroupRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\Stock\Sections\StoreSectionRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SectionController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        $lastStoreNr = Store::latest()->first()?->id + 1 ?? 1;
        $branchs = Branch::get();
        $stores  = Store::get();
        $sections = Section::with('branch', 'branch')->get();
        return view('stock.Section.index', compact(['lastStoreNr', 'branchs', 'stores', 'sections']));
    }

    /**
     * getSectionGroups
     * @param GroupRequest $request
     *
     * @return AnonymousResourceCollection
     */
    public function getSectionGroups(
        GroupRequest $request
    ): AnonymousResourceCollection {

        $groups = Group::where('branch_id', $request->branch_id)->get();
        return GroupResource::collection($groups)
            ->additional([
                'message' => null,
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * store
     *
     * @param StoreSectionRequest $request
     * @return SectionResource
     */
    public function store(
        StoreSectionRequest $request
    ): SectionResource {


        $validatedData = $request->validated(); // Validate the request data
        $groupIds = array_map(function ($item) {
            return $item['id'];
        }, $validatedData['groupIds']);
        $section = Section::create($validatedData);
        $section->groups()->attach($groupIds);
        return SectionResource::make($section)
            ->additional([
                'message' => "تم إانشاء المخزن بنجاح",
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * show
     * @param  Section $section
     * @return StoreResource
     */
    public function show(
        Section $section
    ): SectionResource {
        return SectionResource::make($section)
            ->additional([
                'message' => null,
                'status' => Response::HTTP_OK
            ]);
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
     * @param Section $section
     * @return JsonResponse
     */
    public function destroy(
        Section $section
    ): JsonResponse {
        $section->groups()->detach();
        $section->delete();
        return response()->json([
            'message' => 'تم حذف القسم',
            'status' => Response::HTTP_OK
        ]);
    }
}

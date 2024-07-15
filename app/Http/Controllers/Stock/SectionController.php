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
use App\Http\Requests\Stock\Sections\UpdateSectionRequest;
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
        $lastSectionNr = Section::latest()->first()?->id + 1 ?? 1;
        $branchs = Branch::get();
        $stores  = Store::get();
        $sections = Section::with('branch', 'branch')->paginate(5);
        return view('stock.Section.index', compact(['lastSectionNr', 'branchs', 'stores', 'sections']));
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


        $validatedData = $request->validated();
        $groupIds = array_map(function ($item) {
            return $item['id'];
        }, $validatedData['groupIds']);
        $section = Section::create($validatedData);
        $section->groups()->attach($groupIds);
        return SectionResource::make($section)
            ->additional([
                'message' => "تم إنشاء القسم بنجاح",
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
                'groups' => GroupResource::collection(Group::where('branch_id', $section->branch_id)->get()),
                'status' => Response::HTTP_OK
            ]);
    }
    /**
     * update
     *
     * @param UpdateSectionRequest $request
     * @return SectionResource
     */
    public function update(
        UpdateSectionRequest $request,
        Section $section
    ): SectionResource {
        $validatedData = $request->validated();
        $groupIds = array_map(function ($item) {
            return $item['id'];
        }, $validatedData['groupIds']);
        $section->groups()->detach();
        $section->update($validatedData);
        $section->groups()->attach($groupIds);
        return SectionResource::make($section)
            ->additional([
                'message' => "تم تعديل القسم بنجاح",
                'status' => Response::HTTP_OK
            ]);
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

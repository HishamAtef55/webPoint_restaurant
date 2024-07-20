<?php

namespace App\Http\Controllers\Stock\Material;

use App\Http\Resources\Stock\SectionResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Stock\Sections\FilterSectionRequest;
use App\Models\Stock\Section;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FilterSectionController
{
    /**
     * __invoke
     *
     * @param FilterSectionRequest $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        FilterSectionRequest $request
    ): AnonymousResourceCollection {

        return SectionResource::collection(
            Section::where('branch_id',$request->validated('branch_id'))->get()
        )->additional([
            'message' => null,
            'status' => Response::HTTP_OK
        ]);
    }
}

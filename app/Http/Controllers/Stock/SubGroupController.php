<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Models\Stock\StockGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\SubGroups\StoreSubGroupRequest;

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
        $subGroups = StockGroup::hasParent()->get();
        return view('stock.SubGroups.index', compact('lastSubGroupNr', 'mainGroups', 'subGroups'));
    }

    /**
     * store
     *
     * @param  StoreSubGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        StoreSubGroupRequest $request
    ) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubGroupController  $subGroupController
     * @return \Illuminate\Http\Response
     */
    public function show(SubGroupController $subGroupController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubGroupController  $subGroupController
     * @return \Illuminate\Http\Response
     */
    public function edit(SubGroupController $subGroupController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubGroupController  $subGroupController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubGroupController $subGroupController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubGroupController  $subGroupController
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubGroupController $subGroupController)
    {
        //
    }
}

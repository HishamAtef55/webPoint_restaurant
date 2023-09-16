<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsRequest;
use App\Models\Branch;
use App\Models\Details;
use App\Models\Group;
use App\Models\Item;
use App\Models\ItemsDetails;
use App\Models\Sub_group;
use App\Models\SubGroupDetails;
use http\Env\Response;
use Illuminate\Http\Request;

class Item_DetailsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');

    }
    ################################ Start Search Item Details ###################
    public function item_detalis(Request $request)
    {
        if($request->select == '1')
        {
            $data = Item::where('branch_id',$request->Branch)->get();
            return response()->json($data);

        }
        elseif ($request->select == '2')
        {
            $data = Sub_group::where('branch_id',$request->Branch)->get();
            return response()->json($data);
        }

    }
    ################################ End Search Item Details ###################

    ################################ Start Search Item Details ###################
    public function extract_details_table(Request $request)
    {
        if($request -> type == '1')
        {
            $Item = Item::with('Details') -> where('id',$request -> select) -> get();
            return response()->json($Item);

        }
        elseif ($request -> type == '2')
        {
            $items=Sub_group::with('Details')
                ->where('id',$request -> select) -> get();
            return response()->json($items);
        }

    }
    ################################ End Search Item Details ###################

    ################################ Start Save Item Details ###################
    public function save_item_details(Request $request)
    {
        $data = ItemsDetails::create
                ([
                    'name' => $request->new_item,
                    'branch_id' =>$request->branch
                ]);
        if($data)
        {
            return response()->json
            ([
                'status' => true,
            ]);
        }

    }
    ################################ End Save Item Details   ###################


    ################################ Start Search Item Details   ###################
    public function Search_item_details(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = Details::where('name', 'LIKE', '%' . $query . "%")->get();
            if($data)
            {
                return response()->json($data);
            }
        }
    }

    ################################ End Search  Item Details   ###################


    ################################ Start Delete  Item Details   ###################
    public function delete_item_details(Request $request)
    {
        if($request -> type == '1')
        {
            $data = ItemsDetails::where('item_id',$request -> idItem)
                ->where('detail_id',$request -> Details_ID)
                ->delete();
        }
        elseif($request -> type == '2')
        {
            $items=Item::where('branch_id',$request->branch)
                ->where('sub_group_id',$request->idItem)
                ->select(['id'])
                ->get();
            $length_items = sizeof($items);
            foreach($items as $item)
            {
                $data = ItemsDetails::where('branch_id',$request->branch)
                    ->where('item_id',$item -> id)
                    ->where('detail_id',$request -> Details_ID)
                    ->delete();
            }
            $data = SubGroupDetails::where('branch_id',$request->branch)
                ->where('sub_id',$request->idItem)
                ->where('detail_id',$request -> Details_ID)
                ->delete();
        }
    }
    ################################ End Delete   Item Details    ###################

    ################## Start Export Items Details  #############################
    public function export_details(Request $request)
    {
        // Code => if Request Content of the Item
        $det = str_replace(" ","_",$request->details_section);
        if($request->type == '1')
        {

            $length_details = sizeof($request->id_details);
            for($i=0 ; $i<$length_details ; $i++)
            {
                // check for data
                $check = ItemsDetails::where('item_id',$request -> id_item)
                    ->where('detail_id',$request -> id_details[$i])
                    ->get();
                $size_check = sizeof($check);
                if($size_check == '0')
                {
                    $data = ItemsDetails::create([
                    'item_id'       =>$request -> id_item,
                    'detail_id'     =>$request -> id_details[$i],
                    'section'       =>$det,
                    'max'           =>$request -> max,
                    'price'         => '0',
                    'branch_id'     =>$request->branch,
                ]);
                }
            }
        }
        // Code => if Request Content of the SubGroup
        elseif ($request->type == '2')
        {
            $get_items = Item::where('branch_id',$request->branch)
                ->where('sub_group_id',$request -> id_item)
                ->select(['id'])->get();
            foreach($get_items as $it)
            {
                $length_details = sizeof($request->id_details);
                for($i=0 ; $i<$length_details ; $i++)
                {
                    // check for data
                    $check = ItemsDetails::where('item_id',$request -> id_item)
                        ->where('detail_id',$request -> id_details[$i])
                        ->get();
                    $size_check = sizeof($check);
                    if($size_check == '0')
                    {
                        $data = ItemsDetails::create([
                        'item_id'       =>$it->id,
                        'detail_id'     =>$request -> id_details[$i],
                        'section'       =>$det,
                        'max'           =>$request -> max,
                        'price'         => '0',
                        'branch_id'     =>$request->branch,
                    ]);
                    }
                }
            }

            $length_details = sizeof($request->id_details);
            for($i=0 ; $i<$length_details ; $i++)
            {
                // check for data
                $check = SubGroupDetails::where('sub_id',$request -> id_item)
                    ->where('detail_id',$request -> id_details[$i])
                    ->get();
                $size_check = sizeof($check);
                if($size_check == '0')
                {
                    $data = SubGroupDetails::create([
                    'sub_id'       =>$request->id_item,
                    'detail_id'     =>$request -> id_details[$i],
                    'section'       =>$det,
                    'max'           =>$request -> max,
                    'price'         => '0',
                    'branch_id'     =>$request->branch,
                ]);
                }
            }
        }
    }
    ################## End Export Items Details  ###############################

    ################## Start Search Details  ###############################
    public function search_details(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = Details::where('name', 'LIKE', '%' . $query . "%")->get();
            if($data)
            {
                return response()->json($data);
            }
        }
    }
    ################## End Search Details  ###############################
################## Strat Save Details  ###############################
    public function save_details(Request $request)
    {

        $data = Details::create
        ([
            'name'        => $request -> Details,
            'branch_id'   =>$request -> Branch,
        ]);
        if($data)
        {
            return response()->json
            ([
                'status' => true,
            ]);
        }

    }
################## End Save Details  #############################################


################## Start Update price Details  #####################################
    public function update_dettails_price(Request $request)
    {
        if($request -> type == '1')
        {
            $det = str_replace(" ","_",$request->section);

            $data = ItemsDetails::where('item_id',$request -> idItem)
                ->where('detail_id',$request -> Details_ID)
                ->update([
                    'price'     => $request -> price,
                    'section'   => $det,
                    'max'       => $request -> max,
                ]);
        }
        elseif($request -> type == '2')
        {
            $update_sub = SubGroupDetails::where('sub_id',$request->idItem)
                ->where('branch_id',$request->branch)
                ->where('detail_id',$request -> Details_ID)
                ->update([
                    'price' => $request->price,
                    'max'       => $request -> max,
                ]);
            $items=Item::where('sub_group_id',$request->idItem)
                ->select(['id'])
                ->get();
            $length_items = sizeof($items);
            foreach($items as $item)
            {
                $data = ItemsDetails::where('item_id',$item -> id)
                    ->where('detail_id',$request -> Details_ID)
                    ->update([
                        'price'   => $request -> price,
                        'max'       => $request -> max,
                    ]);
            }
        }
    }
################## End Update price Details    #####################################




################## Strat Update and delete Details  ###############################
    public function action_edite(Request $request)
    {
        if ($request->ajax()) {
            if ($request->action == 'edit') {
                $data = array(
                    'name' => $request->name,
                    'price' => $request->price,
                    'select_min' => $request->min,
                    'select_max' => $request->max,
                );
                Details::where('id', $request->id)
                    ->update($data);

                return response()->json($request);
            }

            if ($request->action == 'delete') {
                ItemsDetails::where('detail_id',$request->id)->delete();
                Details::where('id', $request->id)->delete();
            }
            return Response()->json($request);

        }
    }
    ################## End Update and delete Details  ###############################

    ################## Start delete Details Selected  ###############################
    public function view_details_selected_action()
    {
        $branchs = Branch::get()->all();
        $items = Item::get()->all();
        return view('control.delete_details_selected',compact('items','branchs'));
    }

    public function details_selected_action(Request $request)
    {
        if($request->ajax())
        {
            if($request->action == 'delete')
            {
                $deatils = ItemsDetails::where('detail_id',$request->id)->get()->all();
                if(!$deatils)
                    return abort('404');

                $length = sizeof($deatils);

                for($i = 0 ; $i < $length ; $i ++)
                {
                    if($deatils[$i]->item_id == $request->item_id)
                    {
                        ItemsDetails::where('id',$deatils[$i]->id)->delete();
                        return Response()->json($request);
                    }

                }
            }
        }
    }

    public function details_selected(Request $request)
    {
        $Item = Item::find($request -> Item);
        $data  = $Item -> Details;
        return response() -> json($data);
    }
    ################## End delete Details Selected  ###############################


    ################## Start search item by using branch   ###############################
    public function view_select_item(Request $request)
    {
        $data = Item::where('branch_id',$request->branch)
            ->select(['id','name'])->get();
        return response() -> json($data);

    }
    ################## End search item by using branch   ###############################



}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemsRequest;
use App\Http\Requests\ExtraitemRequest;
use App\Models\Branch;
use App\Models\Group;
use App\Models\Item;
use App\Models\menu;
use App\Models\BarcodeItems;
use App\Models\Printers;
use App\Models\ItemPrinters;
use App\Models\extra;
use App\Models\extra_item;
use App\Models\ItemsDetails;
use App\Models\Sub_group;
use App\Models\Wait_order;
use App\Models\Wait_order_m;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Traits\All_Functions;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{

    use All_Functions;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');

    }
    ################################### End ADD ITEMS Control Page ###########################
    public function view_select_item(Request $request)
    {
        $branch =$request->branch_view;
        $data = menu::where('branch_id',$branch)->get();
        if(!empty($data))
        {
            echo '<option disabled selected value="select_branch" class="form-select-placeholder"></option>';
            foreach ($data as $row)
            {
                echo '<option value="'.$row->id.'">'.$row->name.'</option>';
            }
        }else{
            echo '<option value="">Not Available Menu</option>';
        }

    }
    ################################### End ADD ITEMS Control Page ###########################

    ################################### End ADD ITEMS Control Page ###########################
    public function select_item_sub_group(Request $request)
    {
        $branch =$request->group;
        $data = Sub_group::where('group_id',$branch)->get();
        if(!empty($data))
        {
            echo '<option disabled selected value="select_branch" class="form-select-placeholder"></option>';
            foreach ($data as $row)
            {
                echo '<option value="'.$row->id.'">'.$row->name.'</option>';
            }
        }else{
            echo '<option value="">Not Available Menu</option>';
        }

    }

    public function search_item_newfunction(Request $request){
        $items  = Item::limit(1)->with('Barcode','Printer')->where('id',$request->id_item)->first();
        $check = extra::limit(1)->where(['name'=>$items->name])->count();
        return response()->json(['items'=>$items,'extra'=>$check]);
    }

    public function save_item(ItemsRequest $request)
    {
        $active = 0;
        if(isset($request->active)){
            $active = 1;
        }
        $barcode = explode('+', $request->barcode);
        $file = 'not_found.jpg';
        if ($request->image != null) {
            $file = $this->saveimage($request->image, 'control/images/items');
        }
        if ($request->extra == "on") {
            $data = extra::create
            ([
                'name' => $request->name,
                'chick_name' => $request->chick_name,
                'slep_name' => $request->slep_name,
                'wight' => $request->wight,
                'unit' => $request->unit,
                'price' => $request->price,
                'time_during' => $request->during_time,
                'image' => $file,
                'branch_id' => $request->branch,
                'menu_id' => $request->menu,
                'group_id' => $request->group,
                'sub_group_id' => $request->subgroup,
                'calories' => $request->calories_time,
                'cost_price'=>$request->cost_price,
            ]);
        }
        $data = Item::create
        ([
            'name' => $request->name,
            'note' => $request->note,
            'chick_name' => $request->chick_name,
            'slep_name' => $request->slep_name,
            'price' => $request->price,
            'takeaway_price' => $request->takeaway_price,
            'dellvery_price' => $request->dellvery_price,
            'cost_price' => $request->cost_price,
            'time_during' => $request->during_time,
            'wight' => $request->wight,
            'unit' => $request->unit,
            'image' => $file,
            'branch_id' => $request->branch,
            'menu_id' => $request->menu,
            'group_id' => $request->group,
            'sub_group_id' => $request->subgroup,
            'calories' => $request->calories_time,
            'active' => $active,
        ]);
        foreach ($barcode as $bar) {
            $code = BarcodeItems::create([
                'item' => $data->id,
                'barcode' => $bar,
                'branch' => $request->branch,
            ]);
        }

        if (isset($request->printers)){
            foreach ($request->printers as $printer) {
                $code = ItemPrinters::create([
                    'item_id' => $data->id,
                    'printer' => $printer,
                    'branch_id' => $request->branch,
                ]);
            }
        }

        if ($data)
        {
            return response()->json([
                'status'=>true,
                'image'=>$request->image
            ]);
        }

    }
    ################################### End ADD ITEMS Control Page ###########################


    ################################## Start View Update Item ################################
    public function View_update_item()
    {
        $branchs = Branch::get() -> all();
        $printers = Printers::where(['active'=>'1'])->get();

        return view('control.update_item',compact('branchs','printers'));
    }

    public function search_item(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            if($request->menu == "" && $request->group == "" && $request->sub_group == "")
            {
                $data = Item::where('branch_id', $request->branch)
                ->where('name', 'LIKE', '%' . $query . "%")
                -> get();
            }
            else if($request->group == "" && $request->sub_group == "")
            {
                $data = Item::where('branch_id', $request->branch)
                    ->where('menu_id', $request->menu)
                    ->where('name', 'LIKE', '%' . $query . "%")
                    -> get();
            }
            else if($request->sub_group == "")
            {
                $data = Item::where('branch_id', $request->branch)
                    ->where('menu_id', $request->menu)
                    ->where('group_id', $request->group)
                    ->where('name', 'LIKE', '%' . $query . "%")
                    -> get();
            }else if($request->menu != "" && $request->group != "" && $request->sub_group != ""){
                $data = Item::where('branch_id', $request->branch)
                    ->where('menu_id', $request->menu)
                    ->where('group_id', $request->group)
                    ->where('sub_group_id', $request->sub_group)
                    ->where('name', 'LIKE', '%' . $query . "%")
                    -> get();
            }
            return Response()->json($data);
        }
    }

    function action(Request $request)
    {
        $active = 0;
        if(isset($request->active)){
            $active = 1;
        }
        //update Item
        $data = '';
        if($request->image != null){
            $file = $this->saveimage($request->image,'control/images/items');
            $data = Item::limit(1)->where('id',$request->id)->update
            ([
                'name'                  =>$request->name,
                'note'                  =>$request->note,
                'chick_name'            =>$request->chick_name,
                'slep_name'             =>$request->slep_name,
                'price'                 =>$request->price,
                'takeaway_price'        =>$request->takeaway_price,
                'dellvery_price'        =>$request->dellvery_price,
                'cost_price'            =>$request->cost_price,
                'time_during'           =>$request->during_time,
                'wight'                 =>$request->wight,
                'unit'                  =>$request->unit,
                'image'                 =>$file,
                'active'                 =>$active,
                'calories'              =>$request->calories_time,
            ]);
        }else{
            $data = Item::limit(1)->where('id',$request->id)->update
            ([
                'name'                  =>$request->name,
                'note'                  =>$request->note,
                'chick_name'            =>$request->chick_name,
                'slep_name'             =>$request->slep_name,
                'price'                 =>$request->price,
                'takeaway_price'        =>$request->takeaway_price,
                'dellvery_price'        =>$request->dellvery_price,
                'cost_price'            =>$request->cost_price,
                'time_during'           =>$request->during_time,
                'wight'                 =>$request->wight,
                'unit'                  =>$request->unit,
                'calories'              =>$request->calories_time,
                'active'                 =>$active,
            ]);
        }
        $data = Item::limit(1)->where('id',$request->id)->first();
        // Update Barcode
        $barcode = explode('+' , $request->barcode);
        foreach($barcode as $bar){
            if(BarcodeItems::limit(1)->where('barcode',$bar)->count() > 0)
            {

            }else{
                $code = BarcodeItems::create([
                    'item'    => $request->id,
                    'barcode' => $bar,
                    'branch'  => $request->branch,
                ]);
            }
        }
        if($request->extra == 'on') {
            if (extra::limit(1)->where(['branch_id' => $request->branch, 'name' => $request->name])->count() > 0) {
                $update_extra = extra::limit(1)->where(['branch_id' => $request->branch, 'name' => $request->realname])
                    ->update([
                        'name'                  =>$data->name,
                        'chick_name'            =>$data->chick_name,
                        'slep_name'             =>$data->slep_name,
                        'price'                 =>$data->price,
                        'cost_price'            =>$data->cost_price,
                        'time_during'           =>$data->time_during,
                        'wight'                 =>$data->wight,
                        'unit'                  =>$data->unit,
                        'image'                 =>$data->image,
                        'calories'              =>$data->calories,
                        'branch_id'             =>$data->branch_id,
                        'menu_id'               =>$data->menu_id,
                        'group_id'              =>$data->group_id,
                        'sub_group_id'          =>$data->sub_group_id
                    ]);
            }else{
                $add_Extra = extra::create([
                    'name'                  =>$data->name,
                    'chick_name'            =>$data->chick_name,
                    'slep_name'             =>$data->slep_name,
                    'price'                 =>$data->price,
                    'cost_price'            =>$data->cost_price,
                    'time_during'           =>$data->time_during,
                    'wight'                 =>$data->wight,
                    'unit'                  =>$data->unit,
                    'image'                 =>$data->image,
                    'calories'              =>$data->calories,
                    'branch_id'             =>$data->branch_id,
                    'menu_id'               =>$data->menu_id,
                    'group_id'              =>$data->group_id,
                    'sub_group_id'          =>$data->sub_group_id
                ]);
            }
        }

        if($request->printers != null){
            ItemPrinters::where(['branch_id'=>$request->branch ,'item_id'=>$request->id])->delete();
            foreach($request->printers as $printer){
                ItemPrinters::create([
                    'branch_id'=>$request->branch,
                    'item_id'=>$request->id,
                    'printer'=>$printer
                ]);
            }
        }

        if($data){
            return response()->json(['status'=>'true']);
        }
    }

    public function de_action(Request $request){
        $flag = 0;
        if(Wait_order_m::limit(1)->where(['item_id'=>$request->id_item])->count() == 0){
            if(Wait_order::limit(1)->where(['item_id'=>$request->id_item])->count() == 0){
                $del_details = ItemsDetails::where('item_id',$request->id_item)->delete();
                $del_extra = extra_item::where('item_id',$request->id_item)->delete();
                $del_item  = Item::where('id',$request->id_item)->delete();
                $flag = 1;
            }
        }
        if($flag == 1){
            return response()->json(['status'=>'true']);
        }else{
            return response()->json(['status'=>'false']);
        }
    }
    ################################## End View Update Item ##################################
    ################################# Start search item   ########################################
    public function search_select_item(Request $request)
    {
            $items = Item::where('branch_id',$request->branch)
                ->where('menu_id',$request->menu)
                ->where('group_id',$request->group)
                ->where('sub_group_id',$request->sub_group)
                ->get();
            return response() -> json($items);
    }
    ################################# End search item     ########################################

    ################################# Strat Search extra ######################################
        public function search_select_extra(Request $request)
        {
            if ($request->get('query')) {
                $query = $request->get('query');

                $data =extra::where('branch_id',$request->ID)
                    ->where('name', 'LIKE', '%' . $query . "%")->get();
                if($data)
                {
                    return response()->json($data);
                }

            }
        }
    ################################# End Search Extra   ######################################

    ################################# Strat export Extra   ####################################
    public function export_extra(ExtraitemRequest $request)
    {
        foreach($request->extra as $extra)
        {
            $check = extra_item::where('item_id',$request -> item)
                ->where('extra_id',$extra)
                ->get();
            if(sizeof($check) > 0)
            {
                return response() ->json([
                    'status' =>false,
                ]);
            }else{
                $data = extra_item::create([
                    'item_id' => $request->item,
                    'extra_id'=> $extra
                ]);
            }

        }
        if($data)
        {
            return response() ->json([
                'status' =>true,
            ]);
        }

    }
    ################################# End export Extra   ######################################

    ################################# Start update export Extra   #############################
    public function update_export_extra(Request $request)
    {
        $data = extra_item::where('item_id',$request->item)
            ->where('extra_id',$request->extra)
            ->update(['price' => $request->price]);
        if($data){
            return response()->json([
                'status'=>true,
            ]);
        }
    }
    ################################# End update export Extra   ###############################


    ################################# End delete export Extra   ###############################
    public function delete_export_extra(Request $request)
    {
        $data = extra_item::where('item_id',$request->item)
        ->where('extra_id',$request->extra)
        ->delete();
        if($data)
        {
            return response() ->json([
                'status' =>true,
            ]);
        }
    }
    ################################# End delete export Extra   ###############################


    ################################# Start Get Item Extra   ###############################
    public function get_item_extra(Request $request)
    {
        $data = Item::with('Extra')->where('id',$request->item)->get();
        return response() ->json($data);
    }
    ################################# End Get Item  Extra   ###############################

    public function itemWithOutPrinter(Request $request){
        $items = Item::whereDoesntHave('Printer')->get();
        return response()->json(['items'=>$items]);
    }
    public function show_all_item(Request $request){
        if(isset($request->group)){
            $groups = Group::with(['Supgroups','Supgroups.items'])->where(['branch_id'=>$request->branch,'id'=>$request->group])->get();
        }else{
            $groups = Group::with(['Supgroups','Supgroups.items'])->where(['branch_id'=>$request->branch])->get();
        }
        return response()->json([
            'groups'=>$groups,
        ]);
    }
    public function update_item_price(Request $request){
        $update = Item::findorFail($request->itemId);
        $update->cost_price = $request->costPrice;
        $update->price = $request->price;
        $update->dellvery_price = $request->delPrice;
        $update->takeaway_price = $request->togoPrice;
        $update->name = $request->name;
        $update->chick_name = $request->name;
        $update->slep_name = $request->name;
        $update->save();
        return ['status'=>true,'item'=>$update];
    }
    public function update_item_active(Request $request){
        $update = Item::findorFail($request->itemId);
        if($update->active == 1){
            $update->active = 0;
        }else{
            $update->active = 1;
        }
        $update->save();
        return ['status'=>true,'item'=>$update];
    }

}

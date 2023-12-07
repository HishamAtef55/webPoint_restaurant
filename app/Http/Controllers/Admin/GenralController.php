<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Http\Requests\CreateTableRequest;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\ShiftRequest;
use App\Http\Requests\MenuRequest;
use App\Http\Requests\SubGroupRequest;
use App\Models\Branch;
use App\Models\Shift;
use App\Models\Device;
use App\Models\Group;
use App\Models\Locations;
use App\Models\TablesMerge;
use App\Models\menu;
use App\Models\Hole;
use App\Models\Sub_group;
use App\Models\Table;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Traits\uploadfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PharIo\Manifest\RequiresElementTest;
use App\Models\Orders_d;
use App\Models\Order_Print;
use App\Models\Wait_order;
use App\Models\Details_Wait_Order;
use App\Traits\All_Notifications_menu;
use App\Models\Details_Wait_Order_m;
use App\Models\Extra_wait_order;
use App\Models\Extra_wait_order_m;
use App\Models\Orders_m;
use App\Models\Void_d;
use App\Models\Void_m;
use App\Models\Wait_order_m;
use App\Models\SerialCheck;
use App\Models\SerialShift;
use App\Models\Days;
class GenralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('Permission:admin');
    }

    ################################### Start General Control Page ###########################
    public function add_branch(BranchRequest $request)
    {

        $getlast = Hole::max('number_holes') + 1;

        $save = Branch::create([
                    'name' => $request -> name,
                ]);

        DB::table('holes')->insert([
            'branch_id'       => $save->id,
            'min_charge'      => '0',
            'number_holes'    => $getlast,
            'name'            => 'Other',
            'pattern'         => 'O',
            'min'             => '1',
            'max'             => '10000000',
        ]);

        if($save)
        {
            $permission = "branchs-".$request->name;
            Permission::create([
                'name'=>$permission,
                'type'=>'stock'
            ]);
            return response()->json([
                'status'=>true,
            ]);
        }
    }

    public function reset_data(Request $request){
        $data = '';
        switch($request->op){
            case 'order_only':{
                $Orders_d = Orders_d::truncate();
                $SerialCheck = SerialCheck::truncate();
                $Order_Print = Order_Print::truncate();
                $Wait_order = Wait_order::truncate();
                $Details_Wait_Order = Details_Wait_Order::truncate();
                $Details_Wait_Order_m = Details_Wait_Order_m::truncate();
                $Extra_wait_order = Extra_wait_order::truncate();
                $Extra_wait_order_m = Extra_wait_order_m::truncate();
                $Orders_m = Orders_m::truncate();
                $Void_d = Void_d::truncate();
                $Void_m = Void_m::truncate();
                $ser = SerialShift::truncate();
                $Wait_order_m = Wait_order_m::truncate();
                $Days = Days::truncate();
                $alltables = Table::get()->all();
                foreach($alltables as $table){
                    $data = Table::where(['id'=>$table->id])->update([
                        'state'=>0,
                        'user'=>0,
                        'user_id'=>0,
                        'follow'=>0,
                        'master'=>0,
                        'merged'=>0,
                        'guest'=>0,
                        'min_charge'=>0,
                    ]);
                }
            }break;
            case 'all_data':{
                Artisan::call('db:migrate', ['' => 'mymigration', '--table' => 'mytable']);            }
        }
        if($data){
            return response()->json([
                'status'=>true
            ]);
        }
    }

    public function add_menu(MenuRequest $request)
    {
        if(menu::where('branch_id',$request->branch_id)->count() > 0)
        {
            $add = menu::create(
                [
                    'name'=>$request->name,
                    'branch_id'=>$request->branch_id,
                    'activation' =>'Show',
                ]
            );
            if ($add)
                return response()->json([
                    'status'=>true,
                ]);
        }else{
            $add = menu::create(
                [
                    'name'      =>$request->name,
                    'branch_id' =>$request->branch_id,
                    'active'    => 1,
                    'activation' =>'Show',
                ]
            );
            if ($add)
                return response()->json([
                    'status'=>true,
                ]);
        }

    }


    public function view_select_(Request $request)
    {
        $branch =$request->branch;
        $data = menu::where('branch_id',$branch)->get();
        return response()->json($data);

    }

    public function view_select_branch (Request $request)
    {
        $branch =$request->branch;
        $data = menu::where('branch_id',$branch)->get();
        return response()->json($data);
    }

    public function view_select_menu(Request $request)
    {
        $group =$request->branch;
        $data = Group::where('menu_id',$group)->get();
        return response()->json($data);
    }

    public function view_select_group(Request $request)
    {
        $data = Sub_group::where('branch_id',$request->branch)
            ->where('menu_id',$request->menu)
            ->where('group_id',$request->group)
            ->get();
        return response()->json($data);
    }

    public function save_group(GroupRequest $request)
    {
        $gr_name = $request->group;
        $data = Group::create([
            'name'      =>$request->group,
            'menu_id'   =>$request->menu,
            'branch_id' =>$request->branch,
        ]);
        if($data)
        {
            return response()->json([
                'status'=>true
            ]);
        }
    }

    public function save_subgroup(SubGroupRequest $request)
    {
        $data = Sub_group::create([
            'name'=>$request->sub_group,
            'group_id'=>$request->group,
            'menu_id'=>$request->menu,
            'branch_id'=>$request->branch,
            'active'=>'Show'
        ]);
        if($data)
        {
            return response()->json([
                'status'=>true
            ]);
        }
    }


    ################################ Start Search Branch #############################################
    public function search_branch(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');

            $data = Branch::where('name', 'LIKE', '%' . $query . "%")->get();
            if($data)
            {
                return response()->json($data);
            }

        }
    }

    ################################ End Search Branch #############################################
    ################################ Start Search Menu #############################################
    public function search_menu(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = menu::with('Branch')
                ->where('branch_id',$request->ID)
                ->where('name', 'LIKE', '%' . $query . "%")
                ->get();
            if($data)
            {
                return response()->json($data);
            }

        }
    }
    ################################ End Search Menu #############################################
    ################################ Start Search Group #############################################

    public function search_group(Request $request)
    {
        if ($request->get('query'))
        {
            $query = $request->get('query');
            if($request->menu == '')
            {
                $data = Group::with('Menu')
                    ->where('branch_id',$request->ID)
                    ->where('name', 'LIKE', '%' . $query . "%")
                    ->get();
            }else{
                $data = Group::with('Menu')
                    ->where('branch_id',$request->ID)
                    ->where('menu_id',$request->menu)
                    ->where('name', 'LIKE', '%' . $query . "%")
                    ->get();
                }
            if($data)
            {
                return response()->json($data);
            }
        }
    }
    ################################ End Search Group #############################################
    ################################ Start Search Sub Group #############################################
    public function search_subgroup(Request $request)
    {

        if ($request->get('query')) {
            $query = $request->get('query');
            if($request->menu == '' && $request->group == '')
            {
                $data = Sub_group::with('Group')
                ->where('branch_id', $request->branch)
                ->where('name', 'LIKE', '%' . $query . "%")
                ->get();
                return response()->json($data);

            }elseif($request->group == '')
            {
                $data = Sub_group::with('Group')
                ->where('branch_id', $request->branch)
                ->where('menu_id', $request->menu)
                ->where('name', 'LIKE', '%' . $query . "%")
                ->get();
                return response()->json($data);
            }
            $data = Sub_group::with('Group')
            ->where('branch_id', $request->branch)
            ->where('menu_id', $request->menu)
            ->where('group_id', $request->group)
            ->where('name', 'LIKE', '%' . $query . "%")
            ->get();
            return response()->json($data);
        }
    }
    ############################# Start  Update Branch ##############################
    public function View_update_branch()
    {
        return view('control.update_branch');
    }

    function branch_action(BranchRequest $request)
    {
        if($request->ajax())
        {
            if($request->action == 'edit')
            {
                $oldName = Branch::whereId($request->id)->first();
                $permission = "branchs-".$oldName->name;
                $updatePermission = Permission::whereName($permission)->first();
                $data = array(
                    'name'	=>	$request->name,
                );
                Branch::where('id', $request->id)
                    ->update($data);
                $updatePermission->name = "branchs-" . $request->name;
                $updatePermission->save();
            }
            if($request->action == 'delete')
            {
                $branch = Branch::whereId($request->id)->first();
                $permission = "branchs-".$branch->name;    
                Permission::limit(1)->whereName($permission)->delete();
                $branch->delete();
            }
            return response()->json($request);
        }
    }
    ############################# End  Update Branch ##############################

    ############################# Start  Update Menu ##############################
    public function view_ubdate_menu()
    {
        $branchs = Branch::get() -> all();
        return view('control.update_menu',compact('branchs'));
    }

    function menu_action(Request $request)
    {
        if($request->ajax())
        {
            if($request->action == 'edit')
            {
                $active = "active".$request->id;
                $priority = 0;
                if($request->per >= '1')
                {
                    $priority = 1;
                }
                $data = array(
                    'name'	      => $request->name,
                    'branch_id'   => $request->branch_id,
                    'activation'  => $request->$active,
                    'active'      => $priority
                );
                if($priority == '1')
                {
                    $update_ = menu::where('branch_id',$request->branch_id)
                        ->get();
                    foreach($update_ as $up)
                    {
                        menu::where('id', $up->id)
                        ->update([
                            'active' => 0,
                        ]);
                    }
                }
                menu::where('id', $request->id)
                    ->update($data);
                return response()->json($request);
            }
            if($request->action == 'delete')
            {
                $count = 0;
                $data = menu::whereDoesntHave('Groups')->get();
                foreach($data as $menu)
                {
                    if($menu->id == $request->id)
                    {
                        menu::where('id',$request->id)->delete();
                        return Response()->json($request);
                        $count++;
                    }
                }
                if($count == 0)
                {
                    return Response()->json([
                        "status" => 'false',
                    ]);
                }
            }

        }
    }
    ############################# End  Update Menu ##############################

    ############################# Start Update Group ###############################
    public function View_update_group()
    {
        $branchs = Branch::get()->all();
        return view ('control.update_group',compact('branchs'));
    }

    function group_action(Request $request)
    {
        if($request->ajax())
        {
            if($request->action == 'edit')
            {
                $data = array(
                    'name'	=>	$request->name,
                );
                Group::where('id', $request->id)
                    ->update($data);

                return response()->json($request);
            }

            if($request->action == 'delete')
            {
                $count  = 0;
                $data = Group::whereDoesntHave('Supgroups')->get();
                $length = sizeof($data);
                for($i = 0 ; $i < $length ; $i ++)
                {
                    if($data[$i]->id == $request->id)
                    {
                        Group::where('id',$request->id)->delete();
                        $count++;
                        return Response()->json($request);
                    }
                }
                if($count == 0)
                {
                    return Response()->json([
                        'status'=>'false',
                    ]);
                }
            }

        }
    }

    ############################# End Update Group   ###############################


    ############################# Start Update Sub Group   ###############################
    public function View_update_subgroup()
    {
        $branchs = Branch::get()->all();
        return view('control.update_subgroup',compact('branchs'));
    }
    function subgroup_action(Request $request)
    {
        if ($request->ajax()) {
            $active = "active".$request->id;
            if ($request->action == 'edit') {

                $data = array(
                    'name'   => $request->name,
                    'active' => $request->$active
                );
                Sub_group::where('id', $request->id)
                    ->update($data);

                return response()->json($request);
            }

            if ($request->action == 'delete') {
                $count  = 0;
                $data = Sub_group::whereDoesntHave('items')->get();
                $length = sizeof($data);
                for($i = 0 ; $i < $length ; $i ++)
                {
                    if($data[$i]->id == $request->id)
                    {
                        Sub_group::where('id',$request->id)->delete();
                        $count++;
                        return Response()->json($request);
                    }
                }
                if($count == 0)
                {
                    return Response()->json([
                        'status'=>'false',
                    ]);
                }
            }

        }
    }
    ############################# End Update Sub Group     ###############################

    ####################################### Start Control Add Tables ###############################
    public function add_new_table(Request $request)
    {
        if($request->type == 'other'){
            $new_name = str_replace(' ','-',$request->tableName);
            if(Table::where(['branch_id'=>$request->branch_id,'table_id'=>$new_name])->count() > 0){
                return response()->json(['status'=>'false','msg'=>'Table Oredy Exist']);
            }
            $data = Table::create([
                'state'           => '0' ,
                'booked_up'       => '0',
                'number_table'    => $new_name,
                'table_id'        => $new_name,
                'branch_id'       => $request->branch_id,
                'no_of_gest'      => '1',
                'circle'          => '0',
                'hole'            =>$request ->hole,
                'guest'           =>1,
                'user_id'         =>0,
                'user'            =>0,

            ]);
            return response()->json(['status'=>'true','msg'=>'Tables Saved']);
        }else{
            $last_table = $request->startNum + $request->no_of_tables ;
            $table      = $request->startNum;
            $max        = $request->maxHole;
            for($i=$request->startNum ; $i < $last_table ; $i++){
                if($max <= $table){
                    break;
                    return response()->json(['status'=>'false','msg'=>'Hole is Colded']);
                }
                $data = Table::create([
                    'state'           => '0' ,
                    'booked_up'       => '0',
                    'number_table'    => $request->pattern .'-'. $table,
                    'table_id'        => $request->pattern .'-'. $table,
                    'branch_id'       => $request->branch_id,
                    'no_of_gest'      => $request -> no_of_chair,
                    'circle'          => $request ->circle,
                    'hole'            =>$request ->hole,
                    'guest'           =>1,
                    'user_id'         =>0,
                    'user'            =>0,
                ]);
                $table++;
            }
            return response()->json(['status'=>'true','msg'=>'Tables Saved']);
        }
    }
    ###################################### END Control Add Tables ###############################

    ############################ Start Search tables #############################################
    public function search_holse(Request $request)
    {
        $hole = Table::with(['Reservation','mainHole'])
            ->where('branch_id',Auth::user()->branch_id)
            ->where('hole',$request->hole_num)->get();
        return response()->json($hole);
    }
    public function search_holse_admin(Request $request)
    {
        $hole = Table::with('Reservation')
            ->where('branch_id',$request->branch)
            ->where('hole',$request->hole_num)->get();
        return response()->json($hole);
    }
    ################################# End Search tables ########################################



    ################################# Start Motion tables ########################################
    public function motion_table(Request $request)
    {
        $table = 0 ;
        if($request->tableNumber <= 9)
        {
            $get_len = strlen($request->tableNumber);
            if($get_len > 1)
            {
                $table = $request->tableNumber;
            }else
            {
                $table = '0' . $request->tableNumber;
            }

        }else{
            $table =  $request->tableNumber;
        }
        $data = Table::where('branch_id',$request->branch_id)
                ->where('hole',$request->hole)
                ->where('number_table',$table)->update([
                    'left'  => $request->left,
                    'top'   => $request->top
        ]);
    }

    public function resize_table(Request $request)
    {
        $table = 0 ;
        if($request->tableNumber <= 9)
        {
            $get_len = strlen($request->tableNumber);
            if($get_len > 1)
            {
                $table = $request->tableNumber;
            }else
            {
                $table = '0' . $request->tableNumber;
            }

        }else{
            $table =  $request->tableNumber;
        }
        $data = Table::where('branch_id',$request->branch_id)
                ->where('hole',$request->hole)
                ->where('number_table',$table)->update([
                    'height' => $request->height,
                    'width'  => $request->width
        ]);
        return $table;
    }
    ################################# End Motion tables   ########################################



    ################################# Start Save Holes   ########################################
    public function add_new_hole(Request $request)
    {
        if(Hole::where(['branch_id'=>$request->branch,'name'=>$request->holeName])->count() > 0){
            $data=Hole::where(['branch_id'=>$request->branch,'name'=>$request->holeName])->update([
                'max'          =>$request->max,
            ]);
            return response()->json(['status'=>'true','msg'=>'Updated Hole']);
        }
        if(Hole::where(['branch_id'=>$request->branch,'pattern'=>$request->pattern])->count() > 0){
            return response()->json(['status'=>'false','msg'=>'This pattern oredy exist']);
        }else{
            $data=Hole::create([
                'number_holes' =>$request->holeNum,
                'name'         =>$request->holeName,
                'branch_id'    =>$request->branch,
                'pattern'      =>$request->pattern,
                'min'          =>$request->min,
                'max'          =>$request->max,
            ]);
            Permission::create(['name' => $request->holeName . '-hole','type'=>'pos']);
            return response()->json(['status'=>'true','msg'=>'Saved Hole']);
        }
    }


    ################################# End Save Holes   ########################################


    ################################# Start Del Holes   ######################################
    public function del_hole(Request $request)
    {
        $branch = Auth::user()->branch_id;
        $name_hole = Hole ::limit(1)->where(['branch_id'=>$branch,'number_holes'=>$request->holeattr])->select(['name'])->first();
        $per_name = $name_hole->name . '-hole';
        $per = Permission::limit(1)->where('name',$per_name)->first();
        DB::table('role_has_permissions')->where(['permission_id'=>$per->id])->delete();
        $per = Permission::limit(1)->where('name',$per_name)->delete();
        $del_table = Table::where(['branch_id'=>$branch,'hole'=>$request->holeattr])->delete();
        $del_hole  = Hole ::where(['branch_id'=>$branch,'number_holes'=>$request->holeattr])->delete();
    }
    ################################# End Del Holes   ########################################


    ################################# Start Del Holes   ######################################
    public function del_table(Request $request)
    {
        if(Table::where(['branch_id'=>$request->branch,'hole'=>$request->hole,'number_table'=>$request->table,'state'=>1])->count() > 0)
        {
            return response()->json(['status'=>'false']);

        }else{
            $del_table = Table::where(['branch_id'=>$request->branch,'hole'=>$request->hole,'number_table'=>$request->table])
            ->delete();
            return response()->json(['status'=>'true']);
        }

    }
    ################################# End Del Holes   ########################################
    public function get_holes(Request $request)
    {
        $hole = Hole::where('branch_id',$request->branch)
            ->get();
        return response()->json($hole);
    }
    ################################# Start  Locations Code   ##############################
    public function save_location(Request $request)
    {
        $newrec = '';
        if(Locations::limit(1)->where(['branch_id'=>$request->branch,'location'=>$request->location])->count() > 0)
        {
            $newrec = Locations::limit(1)->where('id',$request->id)->update([
                'location' =>$request->location,
                'price'    =>$request->price,
                'time'     =>$request->time,
                'branch_id'=>$request->branch,
                'pilot_value'=>$request->pilotValue,
            ]);
        }else{
            $newrec = Locations::create([
                'location' =>$request->location,
                'price'    =>$request->price,
                'time'     =>$request->time,
                'branch_id'=>$request->branch,
                'pilot_value'=>$request->pilotValue,
            ]);
        }

        if($newrec)
        {
            return response()->json(['status'=>'true','id'=>$newrec->id]);
        }
    }
    public function update_location(Request $request)
    {
        if($request->action == 'edit')
        {
            $uprec = Locations::limit(1)->where('id',$request->id)->update([
                'location' =>$request->location,
                'price'    =>$request->price,
                'time'     =>$request->time,
                'pilot_value'=>$request->pilotValue,
            ]);
        }
        if($request->action == 'delete')
        {
            Locations::where('id', $request->id)
                ->delete();
        }
        return response()->json($request);
    }
    public function Search_location(Request $request)
    {
        $locations = Locations::where('branch_id',$request->branch)->get();
        return $locations;
    }
    ################################# End    Locations Code   ##############################


    public function save_shift(Request $request){
        $check = Shift::where('branch_id',$request->branch_id)->max('shiftid');
        $check ++ ;
        $status = 0;
        if($check == 1){
            $status = 1 ;
        }
        $save = Shift::create([
            'shift'     => $request -> name,
            'branch_id' => $request -> branch_id,
            'shiftid'   => $check,
            'status'    => $status,
        ]);
        if($save)
        {
            return response()->json([
                'status'=>true,
            ]);
        }
    }

    public function search_shift(Request $request){
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = Shift::with('Branch')
                ->where('branch_id',$request->ID)
                ->where('shift', 'LIKE', '%' . $query . "%")
                ->get();
            if($data)
            {
                return response()->json($data);
            }

        }
    }

    public function tableshift_action(Request $request){
        if($request->ajax())
        {
            if($request->action == 'edit')
            {
                $data = array(
                    'shift'	=>	$request->name,
                );
                Shift::where('id', $request->id)
                    ->update($data);
            }
            if($request->action == 'delete')
            {
                Branch::where('id', $request->id)
                    ->delete();
            }
            return response()->json($request);
        }
    }


    // Function Page in Printrers
    public function view_printers (Request $request){

    }



}

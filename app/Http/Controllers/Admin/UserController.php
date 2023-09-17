<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Models\Branch;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\All_Functions;

class UserController extends Controller
{
    use All_Functions;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin');
    }
    ################################### Start ADD User Control Page #########################
    public function save_user(JobRequest $request)
    {
        if($request->password == $request->confirm_password)
        {
            if($request->discount_ratio == null){$request->discount_ratio = 0;}
            if($request->dialy_salary == null){$request->dialy_salary = 0;}
            if($request->mopile == null){$request->mopile = 0;}
            $file = "not_found.jpg";
            if(isset($request->image)){
                $file = $this->saveimage($request->image,'control/images/users');
            }
            $pass = Hash::make($request->password);
            $data = User::create
            ([
                'name'                    =>$request->name,
                'email'                   =>$request->email,
                'password'                =>$pass,
                'job_id'                  =>$request->position,
                'branch_id'               =>$request->branch,
                'discount_ratio'          =>$request->discount_ratio,
                'dialy_salary'            =>$request->dialy_salary,
                'image'                   =>$file,
                'mopile'                  =>$request->mopile,
                'access_system'           => json_encode($request->type,true),  
            ]);
            if($data)
            {
                return response()->json([
                    'status'=>true
                ]);
            }
        }

    }
    ################################### End ADD User Control Page ###########################


    ################################### Start View Update page #########################
    public function View_update_user()
    {
        $jobs = Job::get()->all();
        $branchs = Branch::get()->all();
        return view('control.update_user',compact('branchs','jobs'));
    }
    ################################### End View Update page ###########################




    ################################### Start Search User Data #########################
    public function search_user(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = User::with(['Branch','Job'])
                ->where('branch_id',$request->branch)
                ->where('name', 'LIKE', '%' . $query . "%")
                ->get();
            return response()->json($data);
        }
    }

    function action(Request $request)
    {
        if($request->ajax())
        {
            if($request->action == 'edit')
            {
                
                // $list =  implode(', ', $request->access_system);
                // return json_encode($list);
                $data_pass = "type".$request->id;
                if($request->pass == null){
                    $data = array(
                        'name'	        =>	$request->name,
                        'email'		    =>	$request->email,
                        'mopile'        =>	$request->mopile,
                        'job_id'        =>	$request->$data_pass,
                        'dialy_salary'	=>	$request->dialy_salary,
                        'access_system' => $request->access_system,
                    );
                }else{
                    $data = array(
                        'name'	        =>	$request->name,
                        'email'		    =>	$request->email,
                        'mopile'        =>	$request->mopile,
                        'job_id'        =>	$request->$data_pass,
                        'dialy_salary'	=>	$request->dialy_salary,
                        'access_system' => $request->access_system,
                        'password'      =>Hash::make($request->pass),
                    );
                }
                User::where('id', $request->id)
                    ->update($data);
            }
            if($request->action == 'delete')
            {
                User::where('id', $request->id)
                    ->delete();
            }
            return response()->json($request);
        }
    }

    ################################### End Search User Data ###########################
}

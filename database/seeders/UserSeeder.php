<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         $user = User::create([
             'name'            => 'Admin',
             'mopile'          => '111',
             'image'           => '111',
             'branch_id'       => '1',
             'discount_ratio'  => '0',
             'job_id'          => '1',
             'dialy_salary'    => '1',
             'email'           => 'admin',
             'roles_name'      => 'admin-pos',
             'access_system'   => '["pos","stock"]',
             'password' => Hash::make('0011'),
         ]);


         DB::table('branchs')->insert([
             'name'            => 'Main',
         ]);

         DB::table('shifts')->insert([
             'shiftid'   =>'1',
             'shift'     =>'Morning',
             'branch_id' =>'1',
             'status'    =>'1'
         ]);

         DB::table('holes')->insert([
             'branch_id'       => '1',
             'min_charge'      => '0',
             'number_holes'    => '1',
             'name'            => 'Other',
             'pattern'         => 'O',
             'min'             => '1',
             'max'             => '10000000',
         ]);

         $role = Role::create(['name' => 'admin-pos' , 'type'=>'pos']);
         $permissions = Permission::pluck('id','id')->all();
         $role->syncPermissions($permissions);
         $user->assignRole([$role->id]);
     }
}

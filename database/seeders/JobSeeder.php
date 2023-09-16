<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $alljob = [ 'Cashier',
          'Capitain',
          'Pilot',
          'Waiter',
          'Take Away',
          'Car Service',
          'Other',
      ];
      foreach($alljob as $job){
        DB::table('jobs')->insert([
            'name' => $job,
        ]);
      }
    }
}

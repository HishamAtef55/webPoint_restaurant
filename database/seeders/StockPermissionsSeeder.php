<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class StockPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'branchs-Main',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission , 'type'=>'stock']);
        }
    }
}

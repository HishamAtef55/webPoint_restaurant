<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
           'options',
                'menu',
                'copy check',
                'copy close shift',
                'discount',
                'insert value discount',
                'move to',
                'guests',
                'close shift',
                'close day',
           'dien-in',
           'open-tables',
           'customer',
           'to order',
           'reservation',
           'accupy',
           'min-charge',
           'transfer',
           'arrow check',
           'void items',
           'take order',
           'pay',
           'print check',
           'print check more than once',
           'pay check',
           'change hold',
           'change service',
           'cash',
           'credit',
           'hospatility',
           'delivery',
                'delivery orders',
                'to pilot',
                    'add pilot',
                    'edite delivery',
                    'remove delivery',
                'pilot account',
                    'change pilot',
                    'print check delivery',
                    'pay check delivery',
                'hold delivery',
                'hold delivery list',
                    'take delivery hold',
                    'edite delivery hold',
                    'remove delivery hold',
            'to-go',
                'to-go orders',
                    'edite to-go',
                    'remove to-go',
                'hold to-go',
                'hold to-go list',
                    'take togo hold',
                    'edite togo hold',
                    'remove togo hold',
                'total to-go',
            'repoerts',
            'admin',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'Other-hole',
        ];
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}

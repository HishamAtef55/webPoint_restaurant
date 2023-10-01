<?php

namespace Database\Seeders;

use App\Models\stock_subunit;
use App\Models\stock_unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unit=array(
            array('id'=>1,'name'=>'كيلو','size'=>'1000'),
            array('id'=>2,'name'=>'لتر','size'=>'1000'),
            array('id'=>3,'name'=>'عدد','size'=>'1'),
        );
        $sub_unit=array(
            array('id'=>1,'name'=>'جرام','unit_id'=>'1','size'=>'1'),
            array('id'=>2,'name'=>'مللي','unit_id'=>'2','size'=>'1'),
            array('id'=>3,'name'=>'عدد','unit_id'=>'3','size'=>'1'),
        );
        foreach ($unit as $u){
            $create_unit = stock_unit::create([
                'id'   =>$u['id'],
                'name' =>$u['name'],
                'size' =>$u['size'],
            ]);
        }
        foreach ($sub_unit as $su){
            $create_subunit = stock_subunit::create([
                'id'      =>$su['id'],
                'name'    =>$su['name'],
                'size'    =>$su['size'],
                'unit_id' =>$su['unit_id'],
            ]);
        }
    }
}

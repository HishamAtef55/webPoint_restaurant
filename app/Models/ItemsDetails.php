<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsDetails extends Model
{
    protected $fillable=
        [
            'item_id',
            'detail_id',
            'price',
            'section',
            'max',
            'sub_id',
            'branch_id'
        ];
    protected $hidden =
        [
            'updated_at',
            'created_at',
        ];
    protected $table='details_items';
}

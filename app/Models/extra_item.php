<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class extra_item extends Model
{
    protected $table = "extra_item";
    protected $fillable = [

    	'id',
    	'item_id',
    	'extra_id'
    ];
    protected $hidden =
    [
        'updated_at',
        'created_at',
    ];
}

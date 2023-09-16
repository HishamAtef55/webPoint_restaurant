<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class extra extends Model
{
    protected $table = "extras";
    protected $guarded = [];

    public function Items()
    {
        return $this->belongsToMany(Item::class,'extra_item','extra_id','item_id');
    }
}

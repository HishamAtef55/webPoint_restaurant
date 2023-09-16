<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Details extends Model
{
    protected $table ='details';
    protected $fillable = ['id','name','branch_id','pivot'];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = true;

    public function Items()
    {
        return $this->belongsToMany(Item::class,'details_items','detail_id','item_id');
    }

}

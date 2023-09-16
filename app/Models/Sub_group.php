<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\Group;
use App\Models\Details;

class Sub_group extends Model
{
    protected $table = 'sub_groups';
    protected $fillable = ['id','name','group_id','menu_id','branch_id','active'];
    protected $hidden = ['created_at','updated_at'];
    public function items()
    {
        return $this->hasMany(Item:class,'sub_group_id','id');
    }

    public function Group()
    {
        return $this->belongsTo(Group:class,'group_id','id');
    }

    public function Details()
    {
        return $this->belongsToMany(Details:class,'details_sub_group','sub_id','detail_id')
            ->withPivot(['price','section','max']);
    }
}

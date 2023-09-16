<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\menu;
use App\Models\group;
use App\Models\Sub_group;
class Group extends Model
{
    protected $table = 'groups';
    protected $fillable = ['name','menu_id','branch_id'];
    protected $hidden = ['created_at','updated_at'];

    public function Menu()
    {
        return $this->belongsTo(menu::class,'menu_id','id');
    }

    public function Branch()
    {
        return $this->hasManyThrough(group::class,menu::class,'branch_id','menu_id','id','id');
    }

    public function Supgroups()
    {
        return $this->hasMany(Sub_group::class,'group_id','id');
    }
}

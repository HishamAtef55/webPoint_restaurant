<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Models\Branch;
use App\Models\Group;

class menu extends Model
{
    use Notifiable;
    protected $searchable = [
        'columns' => [
            'menus.id' => 10,
            'menus.name' => 10,
            'menus.branch_id' => 10,
        ]
    ];
    protected $table = 'menus';
    protected $fillable=['name','branch_id','active','activation'];
    protected $hidden = ['created_at','updated_at'];

    public function Branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function Groups()
    {
        return $this->hasMany(Group::class,'menu_id','id');
    }
}

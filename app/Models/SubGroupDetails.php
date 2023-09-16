<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Group;

class SubGroupDetails extends Model
{
    protected $fillable=
        [
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
    protected $table='details_sub_group';

    public function Group()
    {
        return $this->belongsTo(Group::class,'group_id','id');
    }
}

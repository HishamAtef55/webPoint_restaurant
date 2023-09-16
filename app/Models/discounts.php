<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;

class discounts extends Model
{
    protected $table='discounts';
    protected $fillable = ['id','name','type','value','branch_id'];
    protected $hidden = ['created_at','updated_at'];

    public function Branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}

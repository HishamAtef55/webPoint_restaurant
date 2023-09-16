<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;

class Shift extends Model
{
    protected $table = 'shifts';
    protected $guarded = [];


    public function Branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}

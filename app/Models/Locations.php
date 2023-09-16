<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;
class Locations extends Model
{
    protected $table = 'customer_location';
    protected $guarded = [];
    public function Branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}

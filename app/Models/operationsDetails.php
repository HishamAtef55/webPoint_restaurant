<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class operationsDetails extends Model
{
    use HasFactory;
    protected $table = 'operations_details';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function op(){
        return $this->belongsTo(operationsMain::class ,'order_id','id');
    }
}

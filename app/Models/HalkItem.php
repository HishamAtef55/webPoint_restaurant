<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HalkItem extends Model
{
    use HasFactory;
    protected $table = 'halk_items';
    protected $guarded = [];
    public function getbranch(){
        return $this->belongsTo(Branch::class,'branch','id');
    }
    public function getsection(){
        return $this->belongsTo(stocksection::class,'section_id','id');
    }
}

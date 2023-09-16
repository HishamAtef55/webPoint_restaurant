<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsItem extends Model
{
    use HasFactory;
    protected $table = 'details_items';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function details(){
        return $this->belongsTo(Details::class,'detail_id','id');
    }
    public function materials(){
        return $this->hasMany(mainDetailsComponent::class,'details','detail_id');
    }
}

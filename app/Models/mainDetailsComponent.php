<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mainDetailsComponent extends Model
{
    use HasFactory;
    protected $table = 'stock_main_details_components';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
    public function materials(){
        return $this->hasMany(detailsComponent::class,'main_id','id');
    }
    public function details(){
        return $this->belongsTo(Details::class , 'details','id');
    }
}

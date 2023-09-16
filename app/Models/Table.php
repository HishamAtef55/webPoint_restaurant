<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wait_order;
use App\Models\Reservation;
use App\Models\Hole;

class Table extends Model
{
    protected $table ='tables';
    protected $guarded = [];
    public function Wait_Oreders()
    {
        return $this->hasMany(Wait_order::class,'table_id','id');
    }
    public function Reservation()
    {
        return $this->hasMany(Reservation::class,'table_id','number_table');
    }

    public function Master_Hole()
    {
        return $this->belongsTo(Hole::class,'number_holes','id');
    }
    public function mainHole(){
        return $this->belongsTo(Hole::class,'hole','number_holes');
    }
}

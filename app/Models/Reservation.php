<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Table;

class Reservation extends Model
{
    protected $table = 'reservation';
    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function Table()
    {
        return $this->belongsTo(Table:class,'table_id','number_table');
    }
}

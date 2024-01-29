<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenBalanceSection extends Model
{
    use HasFactory;
    protected $table = 'stock_open_balance_sections';
    protected $guarded = [];
    public function material(){
        $this->belongsTo(material::class,'material_id','id');
    }
}

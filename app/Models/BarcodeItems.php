<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class BarcodeItems extends Model
{
    protected $table ='barcode_items';
    protected $guarded = [];
    public function Item(){
        return $this->belongsTo(Item::class,'item','id');
    }
}

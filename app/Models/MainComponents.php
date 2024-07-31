<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainComponents extends Model
{
    use HasFactory;
    protected $table = "stock_main_components";
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function Materials()
    {
        return $this->hasMany(ComponentsItems::class, 'item_id', 'item');
    }
}

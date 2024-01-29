<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class manufacturingDetails extends Model
{
    use HasFactory;
    protected $table = 'stock_manufacturing_details';
    protected $guarded = [];
}

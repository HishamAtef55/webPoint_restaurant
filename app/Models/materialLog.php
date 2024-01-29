<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materialLog extends Model
{
    use HasFactory;
    protected $table = 'stock_material_logs';
    protected $guarded = [];
}

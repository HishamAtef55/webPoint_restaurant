<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubUnit extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'stock_subunits';
    protected $hidden = ['created_at','updated_at'];
}

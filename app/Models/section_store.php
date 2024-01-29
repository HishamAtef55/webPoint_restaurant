<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class section_store extends Model
{
    use HasFactory;
    protected  $table ='stock_section_stores';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}

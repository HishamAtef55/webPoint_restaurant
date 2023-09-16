<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class section_group extends Model
{
    use HasFactory;
    protected  $table ='section_groups';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}

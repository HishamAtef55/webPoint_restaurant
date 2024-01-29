<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailsComponent extends Model
{
    use HasFactory;
    protected $table = 'stock_details_components';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}

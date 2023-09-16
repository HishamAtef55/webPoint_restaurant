<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class stockOrdersDetails extends Model
{
    use HasFactory , SoftDeletes;
    protected $table = 'stock_orders_details';
    protected $guarded = [];
}

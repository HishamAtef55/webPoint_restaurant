<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class backToSuppliersDetails extends Model
{
    use HasFactory;
    protected  $table ='back_to_suppliers_details';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];
}

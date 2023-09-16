<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPrinters extends Model
{
    protected $table ='item_printers';
    protected $guarded = [];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'data' => 'array',
    ];
}

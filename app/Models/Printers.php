<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printers extends Model
{
    protected $table ='printers';
    protected $guarded = [];

    protected $hidden =[
        'created_at',
        'updated_at',
    ];
}

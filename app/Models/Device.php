<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    protected $fillable = [
        'id',
        'id_device',
        'device_number',
        'printer_invoice',
        'branch_id'
    ];
    protected $hidden = [
        'created_at',
        'uploaded_at',
    ];
}

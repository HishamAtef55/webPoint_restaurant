<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevicePrinters extends Model
{
    use HasFactory;
    protected $table = "device_printers";
    protected $guarded = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car_services extends Model
{
    protected $table = 'car_Services';
    protected $fillable = [
        'id',
        'fast_check',
        'printers_input',
        'print_invoice',
        'reservation_receipt',
        'car_service_receipt',
        'slip',
        'service_ratio',
        'invoice_copies',
        'tax',
        'branch',
    ];
    protected $hidden = [
        'created_at',
        'uploaded_at',
    ];
}

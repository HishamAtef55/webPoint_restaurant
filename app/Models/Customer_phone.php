<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
class Customer_phone extends Model
{
    protected $table = 'customer_phones';
    protected $fillable = [
        'id',
        'branch_id',
        'customer_id',
        'phone'
    ];
    protected $hidden = [
        'created_at',
        'uploaded_at',
    ];


    public function Customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}

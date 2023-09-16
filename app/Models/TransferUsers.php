<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TransferUsers extends Model
{
    protected $table = 'transfer_users';
    protected $guarded = [];


    public function CurrentUser()
    {
        return $this->belongsTo(User::class,'c_user','id');
    }
    public function NewUser()
    {
        return $this->belongsTo(User::class,'n_user','id');
    }
}

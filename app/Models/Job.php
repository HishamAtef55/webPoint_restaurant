<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Job extends Model
{
    protected $table = 'jobs';
    protected $fillable = ['name','id'];
    protected $hidden = ['created_at','updated_at'];
    public function User()
    {
        return $this->hasMany(User::class,'job_id','id');
    }
}

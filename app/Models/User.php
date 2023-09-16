<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Job;
use App\Models\Branch;
class User extends Authenticatable

{

    use Notifiable;
    use HasRoles;
    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'job_id',
        'mopile',
        'branch_id',
        'discount_ratio',
        'dialy_salary',
        'image',
        'password',
        'roles_name',

    ];
    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'password', 'remember_token',

    ];
    /**

     * The attributes that should be cast to native types.

     *

     * @var array

     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles_name' => 'array',
        ];

    public function job()
    {
        return $this->belongsTo(Job::class,'job_id','id');
    }

    public function Branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

}

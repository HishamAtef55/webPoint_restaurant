<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesCategory extends Model
{
    use HasFactory;
    protected $table = 'expenses_categories';
    protected $guarded = [];

    public function Branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}

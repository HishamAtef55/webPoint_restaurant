<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyExpenses extends Model
{
    use HasFactory;
    protected $table = 'daily_expenses';
    protected $guarded = [];
    
    public function category()
    {
        return $this->belongsTo(ExpensesCategory::class,'expense_id','id');
    }
    public function user()
    {
      return $this->belongsTo(User::class,'user_id','id');
    }
}

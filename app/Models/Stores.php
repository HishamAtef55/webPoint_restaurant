<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{

  protected  $table = 'stock_stores';

  protected $guarded = [];

  protected $fillable = [
    'name', 'phone', 'address'
  ];

  public function storgecapacity()
  {
    return $this->hasMany(storage_capacity::class, 'store', 'id');
  }
}

<?php

namespace App\Models\Stock;

use App\Models\Traits\HasSerialNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class StockGroup extends Model
{
    use HasFactory, HasRecursiveRelationships, HasSerialNumber;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'stock_groups';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'parent_id', 'serial_nr'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function hasChildren(): bool
    {
        return (bool) $this->children->count();
    }
}

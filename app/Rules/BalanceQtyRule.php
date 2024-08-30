<?php

namespace App\Rules;

use App\Models\Stock\StoreBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class BalanceQtyRule implements Rule
{
    protected $materialId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($materialId)
    {
        $this->materialId = $materialId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $balance = StoreBalance::where('material_id', $this->materialId)->first()->qty ?? 0;
        return $balance >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The quantity must be less than or equal to the available balance.';
    }
}

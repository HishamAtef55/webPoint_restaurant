<?php

namespace App\Http\Requests\Stock\MainGroups;

use App\Models\Stock\StockGroup;
use Illuminate\Foundation\Http\FormRequest;

class StoreMainGroupRequest extends FormRequest
{

    public const INTIAL_MAIN_STOCK_GROUP_NR = '01';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return
            [
                'name' => ['required', 'string', 'unique:stock_groups,name'],
            ];
    }

    public function messages()
    {
        return
            [
                'name.required' => __('برجاء ادخال اسم المجوعة'),
                'name.unique'   => __('هذة المجموعة موجودة بالفعل'),
            ];
    }

    public function setSerialNr()
    {
        $mainGroup = StockGroup::isRoot()->latest('serial_nr')->first();
        if ($mainGroup) {
            $nextSerialNr = $mainGroup->serial_nr + 1;  // 02
        } else {
            $nextSerialNr = static::INTIAL_MAIN_STOCK_GROUP_NR; // 01
        }
        return str_pad($nextSerialNr, strlen(self::INTIAL_MAIN_STOCK_GROUP_NR), '0', STR_PAD_LEFT);
    }
}

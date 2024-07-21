<?php

namespace App\Http\Requests\Stock\SubGroups;

use App\Models\Stock\StockGroup;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubGroupRequest extends FormRequest
{
    public const INTIAL_SUB_STOCK_GROUP_NR = '1';
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
                'parent_id' => ['required', 'integer', 'exists:stock_groups,id']
            ];
    }

    public function messages()
    {
        return
            [
                'name.required' => __('برجاء ادخال اسم المجوعة'),
                'parent_id.required' => __('برجاءاختيار المجوعة الرئيسية'),
                'name.unique'   => __('هذة المجموعة موجودة بالفعل'),
                'parent_id.exists'   => __('هذة المجموعة الرئيسية غير موجودة'),
            ];
    }

    public function setSerialNr($parentId)
    {
        $parentGroup = StockGroup::find($parentId);
        $subGroup = $parentGroup->children()->latest('serial_nr')->first();
        if ($subGroup) {
            $stockGroupSerialNr = $subGroup->serial_nr;
            $lastDigits = substr($stockGroupSerialNr, -1); //01001
            $nextSerialNr = (int)$lastDigits + 1;
        } else {
            $nextSerialNr = static::INTIAL_SUB_STOCK_GROUP_NR; // 1
        }
        return $parentGroup->serial_nr . $nextSerialNr;
    }
}

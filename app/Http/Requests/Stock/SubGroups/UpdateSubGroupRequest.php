<?php

namespace App\Http\Requests\Stock\SubGroups;

use Illuminate\Validation\Rule;
use App\Models\Stock\StockGroup;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubGroupRequest extends FormRequest
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
                'name' => [
                    'required', 'string',
                    Rule::unique('stock_groups')->ignore($this->route('stockGroup'))
                ],
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

    public function updateSerialNr()
    {

        $stockGroup = $this->route('stockGroup');
        $newParentId = $this->input('parent_id');
        if ($stockGroup->parent_id == $newParentId) {
            return $stockGroup->serial_nr;
        } else {
            $parentGroup = StockGroup::find($this->parent_id);
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
}

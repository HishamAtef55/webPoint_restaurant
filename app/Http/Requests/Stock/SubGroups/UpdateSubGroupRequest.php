<?php

namespace App\Http\Requests\Stock\SubGroups;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubGroupRequest extends FormRequest
{
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
}

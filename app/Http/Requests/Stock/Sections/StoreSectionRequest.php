<?php

namespace App\Http\Requests\Stock\Sections;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
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
                'name'   => ['required', 'string', 'max:100', 'unique:stock_sections,name'],
                'branch_id' => ['required', 'integer', 'exists:branchs,id'],
                'store_id'  => ['required', 'integer', 'exists:stock_stores,id'],
                'groupIds' => ['required', 'array'],
                'groupIds.*' => ['exists:groups,id'],
            ];
    }
    public function messages()
    {
        return
            [
                'name.required'   => __('برجاء ادخال اسم القسم'),
                'branch.required' => __('برجاء اختيار اختيار الفرع'),
                'store.required'  => __('برجاء اختيار المخزن'),
                'groupIds.required'  => __('برجاء اختيار مجموعة'),
                'name.unique'   => __('هذا القسم موجود بالفعل'),

            ];
    }
}

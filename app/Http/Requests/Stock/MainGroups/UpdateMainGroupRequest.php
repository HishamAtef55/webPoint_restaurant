<?php

namespace App\Http\Requests\Stock\MainGroups;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMainGroupRequest extends FormRequest
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
                    // Rule::unique('stock_stores')->ignore($this->route('store'))
                ],
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
}

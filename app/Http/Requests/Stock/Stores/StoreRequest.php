<?php

namespace App\Http\Requests\Stock\Stores;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
                'name' => ['required', 'string', 'unique:stock_stores,name'],
                'phone' => ['nullable', 'numeric'],
                'address' => ['nullable', 'string'],
            ];
    }

    public function messages()
    {
        return
            [
                'name.required' => __('برجاء ادخال اسم المخزن'),
                'name.unique'   => __('هذا المخزن موجود بالفعل'),
            ];
    }
}

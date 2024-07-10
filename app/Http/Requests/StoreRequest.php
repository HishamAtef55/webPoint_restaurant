<?php

namespace App\Http\Requests;

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
                'name' => 'required', 'string', 'unique:stock_stores,name',
                'phone' => 'required', 'integer',
                'address' => 'required', 'string',
            ];
    }

    public function messages()
    {
        return
            [
                'name.required' => __('برجاء ادخال اسم المخزن'),
                'phone.required' => __('برجاء ادخال رقم التليفون'),
                'address.required' => __('برجاء ادخال العنوان'),
                'name.unique'   => __('هذا المخزن موجود بالفعل'),
            ];
    }
}

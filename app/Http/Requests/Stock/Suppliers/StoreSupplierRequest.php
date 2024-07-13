<?php

namespace App\Http\Requests\Stock\Suppliers;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
                'name' => ['required', 'string', 'unique:stock_suppliers,name'],
                'phone' => ['required', 'numeric'],
                'address' => ['required', 'string'],
            ];
    }

    public function messages()
    {
        return
            [
                'name.required' => __('برجاء ادخال اسم المورد'),
                'phone.required' => __('برجاء ادخال رقم الهاتف'),
                'address.required' => __('برجاء ادخال العنوان'),
                'name.unique'   => __('هذا المورد موجود بالفعل'),
            ];
    }
}

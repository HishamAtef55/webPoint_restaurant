<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
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
        return [
            'name'              =>'required|unique:discounts,name',
            'discount_type'    =>'required',
            'value'             =>'required|numeric',
            'branch'             =>'required',
        ];
    }


    public function messages()
    {
        return [
            'name.required'                 =>__('Please Enter Name Of Discount'),
            'name.unique'                   =>__('This Discount already exists.'),
            'discount_type.required'        =>__('Please Select Type Of Discount'),
            'value.required'                =>__('Please Enter Value'),
            'branch.required'               =>__('Please Select Branch'),
            'value.numeric'                 =>__('The Value Only Number'),
        ];
    }
}

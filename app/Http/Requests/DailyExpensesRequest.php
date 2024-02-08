<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyExpensesRequest extends FormRequest
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
            'category'     =>'required',
            'amount'       =>'required|numeric',
        ];
    }
    public function messages()
    {
        return [
            'category.required'    =>__('Select Category Please !'),
            'amount.required'      =>__('Enter Amount Please'),
        ];
    }
}

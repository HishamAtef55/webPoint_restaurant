<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToGoRequest extends FormRequest
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
            'branch'         =>'required',
            'tax'            =>'required',
            'printer'        =>'required',
            'invoice_copies' =>'required',
            'service_ratio'  =>'required',
        ];
    }

    public function messages()
    {
        return
            [
                'branch.required'          => __('Select Branch Pleas !'),
                'tax.required'             => __('Select Tax Pleas !'),
                'printer.required'         => __('Select Printer Pleas !'),
                'invoice_copies.required'  => __('Enter invoice_copies Pleas !'),
                'service_ratio.required'   => __('Enter service_ratio Pleas !'),
            ];
    }
}

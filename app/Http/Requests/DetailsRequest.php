<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailsRequest extends FormRequest
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
            'name'=>'required',
            'branch'=>'required',
        ];
    }

    public function messages()
    {
        return
            [
                'name.required' => __('Enter Name Pleas !'),
                'branch.required'   => __('Select Branch Please !'),
            ];
    }
}

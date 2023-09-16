<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
                'name'=>'required',
                'branch_id'=>'required',
            ];
    }

    public function messages()
    {
        return
            [
                'name.required' => __('Enter Menu Pleas !'),
                'branch_id.required' => __('Select Branch Pleas !'),
            ];
    }
}

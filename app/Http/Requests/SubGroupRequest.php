<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubGroupRequest extends FormRequest
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
            'branch'    =>'required',
            'menu'      =>'required',
            'group'     =>'required',
            'sub_group' =>'required',
        ];
    }

    public function messages()
    {
        return
            [
                'branch.required'     => __('Select Branch Pleas !'),
                'menu.required'       => __('Select Menu Pleas !'),
                'group.required'      => __('Select Group Pleas !'),
                'sub_group.required'  => __('Enter Sub Group Pleas !'),
            ];
    }
}

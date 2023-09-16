<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtraitemRequest extends FormRequest
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
            'branch'                     =>'required|numeric',
            'menu'                       =>'required|numeric',
            'group'                      =>'required|numeric',
            'sub_group'                   =>'required|numeric',
            'extra'                      =>'required|',
            'item'                       =>'required|',
        ];
    }
    public function messages()
    {
        return [
            'branch_view.required'        =>__('Select Branch Please !'),
            'menu.required'               =>__('Select Menu Please !'),
            'group.required'              =>__('Select Group Please !'),
            'subgroup_view.required'      =>__('Select Sub Group Please !'),
            'extra.required'              =>__('Enter Extra Please !'),
            'item.required'               =>__('Enter Item Please !'),
            
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemsRequest extends FormRequest
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
            'barcode'                    =>'unique:barcode_items,barcode',
            'branch'                     =>'required|numeric',
            'menu'                       =>'required|numeric',
            'group'                      =>'required|numeric',
            'subgroup'                   =>'required|numeric',
            'name'                       =>'required|',
        ];
    }
    public function messages()
    {
        return [
            'barcode.unique'              =>__('This barcode already exists.'),
            'branch.required'             =>__('Select Branch Please !'),
            'menu.required'               =>__('Select Menu Please !'),
            'group.required'              =>__('Select Group Please !'),
            'subgroup.required'           =>__('Select Sub Group Please !'),
            'name.required'               =>__('Enter Name Please !'),
        ];
    }
}

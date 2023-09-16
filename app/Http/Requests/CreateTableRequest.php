<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTableRequest extends FormRequest
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
            'no_of_tables' =>'required',
            'no_of_chair'  =>'required',
            'hole'         =>'required',
            'branch_id'    =>'required',
        ];
    }

    public function messages()
    {
        return
        [
            'branch_id.required'    => __('Select Branch Pleas !'),
            'hole.required'         => __('Select Hole Pleas !'),
            'no_of_chair.required'  => __('Select No.Chair Pleas !'),
            'no_of_tables.required' => __('Select No.Tables Pleas !'),
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockGroupRequest extends FormRequest
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
                'name'   =>'required|unique:material_groups,name',
                'from' =>'required',
                'to'  =>'required',
            ];
    }
    public function messages()
    {
        return
            [
                'name.required'   => __('برجاء ادخال اسم المجموعه'),
                'name.unique'     => __('اسم المجموعه موجود بالفعل'),
                'from.required'   => __('برجاء ادخال بداية الترقيم'),
                'to.required'     => __('برجاءادخال نهاية الترقيم'),
            ];
    }
}

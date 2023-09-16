<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialRequest extends FormRequest
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
                'materialId'    =>'required',
                'materialName'  =>'required',
                'subGroup'      =>'required',
                'allGroup'      =>'required',
                'unit'          =>'required',
                'storeMethod'   =>'required',
            ];
    }

    public function messages()
    {
        return
            [
                'materialName.required'   => 'برجاء ادخال اسم الخامة',
                'materialId.required'     => 'برجاء ادخال كود الخامة',
                'subGroup.required'       => 'برجاء تحديد المجموعة الفرعية',
                'allGroup.required'       => 'برجاء تحديد المجموعة الرأيسيسة',
                'unit.required'           => 'برجاء تحديد الوحدة',
                'storeMethod.required'    => 'برجاء تحديد نوع التخزين',
            ];
    }
}

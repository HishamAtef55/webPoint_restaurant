<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
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
                'materialId'    =>'required|unique:stock_materials,code',
                'materialName'  =>'required|unique:stock_materials,name',
                'subGroup'      =>'required',
                'allGroup'      =>'required',
                'unit'          =>'required',
                'storeMethod'   =>'required',
                'section'       =>'required',
            ];
    }

    public function messages()
    {
        return
            [
                'materialName.unique'     => 'اسم الخامة موجود بالفعل',
                'materialName.required'   => 'برجاء ادخال اسم الخامة',
                'materialId.unique'       => 'كود الخامة موجود بالفعل',
                'materialId.required'     => 'برجاء ادخال كود الخامة',
                'subGroup.required'       => 'برجاء تحديد المجموعة الفرعية',
                'allGroup.required'       => 'برجاء تحديد المجموعة الرأيسيسة',
                'unit.required'           => 'برجاء تحديد الوحدة',
                'storeMethod.required'    => 'برجاء تحديد نوع التخزين',
                'section.required'        => 'برجاء تحديد اقل شئ قسم واحد',
            ];
    }
}

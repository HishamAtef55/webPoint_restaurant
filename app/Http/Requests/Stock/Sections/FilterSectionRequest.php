<?php

namespace App\Http\Requests\Stock\Sections;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FilterSectionRequest extends FormRequest
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

                'branch_id' => ['required', 'integer', 'exists:branchs,id'],

            ];
    }
    public function messages()
    {
        return
            [
                'branch.required' => __('برجاء اختيار الفرع'),
            ];
    }
}

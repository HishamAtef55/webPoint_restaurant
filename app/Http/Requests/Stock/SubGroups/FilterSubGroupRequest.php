<?php

namespace App\Http\Requests\Stock\SubGroups;

use Illuminate\Foundation\Http\FormRequest;

class FilterSubGroupRequest extends FormRequest
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
            'parent_id' => ['required', 'integer', 'exists:stock_groups,id']
        ];
    }
}

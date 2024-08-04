<?php

namespace App\Http\Requests\Stock\Material\Recipe;

use App\Enums\Unit;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class RepeatRecipeRequest extends FormRequest
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
            'material_id' => ['required', 'array'],
            'material_id.*' => ['required', 'integer', 'exists:stock_materials,id'],
            'components' => ['required', 'array'],
            'components.*.code' => ['required', 'integer', 'exists:stock_materials,id'],
            'components.*.quantity' => ['required', 'integer', 'min:1'],
            'components.*.price' => ['required'],
            'components.*.unit' => ['required', 'string', new Enum(Unit::class)]
        ];
    }

    public function messages()
    {
        return
            [
                'material_id.required' => __('برجاء اختيار الخامة'),
                'components.required' => __('برجاء اختيار مكونات الخامة'),
            ];
    }
}

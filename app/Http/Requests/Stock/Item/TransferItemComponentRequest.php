<?php

namespace App\Http\Requests\Stock\Item;

use App\Enums\Unit;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class TransferItemComponentRequest extends FormRequest
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
            'branch' => ['required', 'integer', 'exists:branchs,id'],
            'item_id' => ['required', 'array'],
            'item_id.*' => ['integer', 'exists:items,id'],
            'components' => ['required', 'array'],
            'components.*.material_id' => ['required', 'integer', 'exists:stock_materials,id'],
            'components.*.material_name' => ['required', 'string'],
            'components.*.quantity' => ['required', 'integer', 'min:1'],
            'components.*.cost' => ['required', 'numeric', 'min:0'],
            'components.*.unit' => ['required', 'string', new Enum(Unit::class)]
        ];
    }

    public function messages()
    {
        return
            [
                'branch.required' => __('برجاء اختيار الفرع'),
                'branch.exists' => __('الفرع غير متاح'),
                'item_id.required'   => __('برجاء اختيار الصنف'),
            ];
    }
}

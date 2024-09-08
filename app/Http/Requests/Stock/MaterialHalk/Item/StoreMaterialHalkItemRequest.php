<?php

namespace App\Http\Requests\Stock\MaterialHalk\Item;

use App\Enums\PaymentType;
use App\Enums\PurchasesMethod;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreMaterialHalkItemRequest extends FormRequest
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


            'serial_nr' => ['nullable', 'string', 'unique:stock_materials_halk_items,serial_nr'],

            'halk_item_date' => 'required|date',

            'notes' => 'nullable|string',


            'branch_id' => [
                'required',
                'exists:branchs,id'
            ],

            'section_id' => [
                'required',
                'exists:stock_sections,id'
            ],

            'items' => ['required', 'array'],
            'items.*.item_id' => ['required', 'integer', 'exists:stock_components_items,item_id'],
            'items.*.qty' => ['required', 'integer', 'min:1']


        ];
    }
}

<?php

namespace App\Http\Requests\Stock\Exchange;

use App\Rules\BalanceQtyRule;
use App\Enums\PurchasesMethod;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExchangeRequest extends FormRequest
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

            'exchange_nr' => [
                'nullable',
                'string',
                Rule::unique('stock_materials_exchange')->ignore($this->route('exchange')),
            ],
            'notes' => 'nullable|string',

            'exchange_date' => 'required|date',

            'store_id' => [
                'required',
                'integer',
                'exists:stock_stores,id'
            ],

            'section_id' => [
                'required',
                'integer',
                'exists:stock_sections,id'
            ],
            'total' => 'required',
            'materialArray' => 'required',

            'materialArray.*.material_id' => [
                'required',
                'integer',
                'exists:stock_stores_balance,material_id'
            ],
            'materialArray.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],
            'materialArray.*.price' => [
                'required',
                'integer'
            ],
            'materialArray.*.total' => [
                'required',
                'integer'
            ],

            'image' => 'nullable'
        ];
    }
}

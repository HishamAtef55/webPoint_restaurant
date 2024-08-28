<?php

namespace App\Http\Requests\Stock\Exchange;


use App\Enums\PurchasesMethod;
use Illuminate\Foundation\Http\FormRequest;

class StoreExchangeRequest extends FormRequest
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

            'order_nr' => ['nullable', 'string','unique:stock_exchange,order_nr'],

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

            'materialArray' => 'required',
            
            'materialArray.*.material_id' => [
                'required',
                'integer',
                'exists:stock_materials,id'
            ],
            'materialArray.*.qty' => [
                'required',
                'integer'
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

<?php

namespace App\Http\Requests\Stock\Purchases;

use App\Enums\PurchasesMethod;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchasesRequest extends FormRequest
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

            'payment_type' => ['required', 'string'],

            'purchases_method' => ['required', 'string'],

            'serial_nr' => [
                'nullable',
                'string',
                Rule::unique('stock_materials_purchases')->ignore($this->route('purchase')),
            ],

            'notes' => 'nullable|string',


            'tax' => 'required|integer',

            'sumTotal' => 'required',


            'purchases_date' => 'required|date',


            'supplier_id' => [
                'required',
                'exists:stock_suppliers,id'
            ],

            'store_id' => [
                'required_if:purchases_method,' . PurchasesMethod::STORES->value,
            ],

            'section_id' => [
                'required_if:purchases_method,' . PurchasesMethod::SECTIONS->value,
            ],


            'materialArray' => 'required',
            'materialArray.*.material_id' => [
                'required',
                'integer',
                'exists:stock_materials,id'
            ],
            'materialArray.*.expire_date' => [
                'required',
                'date'
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
            'materialArray.*.discount' => [
                'required',
                'integer'
            ],
            'materialArray.*.total' => [
                'required',
                'integer'
            ],

            'purchases_image' => 'nullable'
        ];
    }
}

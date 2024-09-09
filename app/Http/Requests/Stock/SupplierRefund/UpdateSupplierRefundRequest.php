<?php

namespace App\Http\Requests\Stock\SupplierRefund;

use App\Enums\PurchasesMethod;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRefundRequest extends FormRequest
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


            'refund_method' => ['required', 'string'],

            'serial_nr' => ['nullable', 'string',],

            'notes' => 'nullable|string',



            'total' => 'required',


            'refund_date' => 'required|date',


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

            'materialArray.*.total' => [
                'required',
                'integer'
            ],

            'refund_image' => 'nullable'
        ];
    }
}

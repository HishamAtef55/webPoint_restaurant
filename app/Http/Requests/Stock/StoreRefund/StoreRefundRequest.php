<?php

namespace App\Http\Requests\Stock\StoreRefund;

use App\Enums\PaymentType;
use App\Enums\PurchasesMethod;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreRefundRequest extends FormRequest
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



            'serial_nr' => ['nullable', 'string', 'unique:stock_materials_stores_refund,serial_nr'],

            'notes' => 'nullable|string',



            'total' => 'required',


            'refund_date' => 'required|date',

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
                'integer',
                'min:1',
            ],
            
            'materialArray.*.price' => [
                'required',
            ],

            'materialArray.*.total' => [
                'required',
            ],

            'refund_image' => 'nullable'
        ];
    }
}

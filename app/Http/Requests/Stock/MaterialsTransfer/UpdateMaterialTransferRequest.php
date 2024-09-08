<?php

namespace App\Http\Requests\Stock\MaterialsTransfer;

use App\Enums\PurchasesMethod;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialTransferRequest extends FormRequest
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

            'transfer_type' => ['required', 'string'],

            'serial_nr' => ['nullable', 'string'],

            'transfer_date' => 'required|date',

            'notes' => 'nullable|string',

            'total' => 'required',

            'image' => 'nullable',


            'from_store_id' => [
                'required_if:transfer_type,' . PurchasesMethod::STORES->value,
                'exists:stock_stores,id'
            ],

            'to_store_id' => [
                'required_if:transfer_type,' . PurchasesMethod::STORES->value,
                'exists:stock_stores,id'
            ],

            'from_section_id' => [
                'required_if:transfer_type,' . PurchasesMethod::SECTIONS->value,
                'exists:stock_sections,id'
            ],

            'to_section_id' => [
                'required_if:transfer_type,' . PurchasesMethod::SECTIONS->value,
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
                'integer'
            ],
            'materialArray.*.total' => [
                'required',
                'integer'
            ],


        ];
    }
}

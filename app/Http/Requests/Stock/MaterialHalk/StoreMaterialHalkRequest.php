<?php

namespace App\Http\Requests\Stock\MaterialHalk;

use App\Enums\PaymentType;
use App\Enums\PurchasesMethod;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreMaterialHalkRequest extends FormRequest
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

            'halk_type' => ['required', 'string'],

            'serial_nr' => ['nullable', 'string', 'unique:stock_materials_halk,serial_nr'],

            'halk_date' => 'required|date',

            'notes' => 'nullable|string',

            'total' => 'required',

            'image' => 'nullable',


            'store_id' => [
                'required_if:transfer_type,' . PurchasesMethod::STORES->value,
                'exists:stock_stores,id'
            ],

            'section_id' => [
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

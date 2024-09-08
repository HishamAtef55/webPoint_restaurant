<?php

namespace App\Http\Requests\Stock\Exchange;


use App\Rules\BalanceQtyRule;
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

            'order_nr' => ['nullable', 'string', 'unique:stock_materials_exchange,order_nr'],

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
                function ($attribute, $value, $fail) {
                    // Extract the index from the attribute
                    preg_match('/materialArray\.(\d+)\.qty/', $attribute, $matches);
                    $index = $matches[1];

                    // Get the corresponding material_id
                    $materialId = $this->input("materialArray.$index.material_id");

                    // Pass the material_id to the custom rule
                    $rule = new BalanceQtyRule($materialId);
                    if (!$rule->passes($attribute, $value)) {
                        $fail($rule->message());
                    }
                },
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

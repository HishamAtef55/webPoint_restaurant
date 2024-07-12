<?php

namespace App\Http\Requests\Stock\Stores;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        return
            [
                'name' => [
                    'nullable', 'string',
                    // Rule::unique('stock_stores')->ignore($this->route('id'))
                ],
                'phone' => ['nullable', 'numeric'],
                'address' => ['nullable', 'string'],
            ];
    }
}

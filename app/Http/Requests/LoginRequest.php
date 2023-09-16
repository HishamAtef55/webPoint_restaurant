<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'password'=>'required|numeric'
        ];
    }

    public function messages()
    {
        return
            [
                'password.required' => __('please Enter password'),
                'password.numeric' => __('Please The Password Only Numbers'),
            ];
    }
}

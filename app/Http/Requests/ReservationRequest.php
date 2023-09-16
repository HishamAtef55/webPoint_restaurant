<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'userName'      =>'required',
            'phone_number'  =>'required',
            'cash'          =>'required',
            'date'          =>'required',
            'time_from'     =>'required',
        ];
    }

    public function messages()
    {
        return
            [
                'userName.required'        => __('Enter Name Pleas !'),
                'phone_number.required'    => __('Enter Phone Pleas !'),
                'cash.required'            => __('Enter Cash Pleas !'),
                'date.required'            => __('Select Date Pleas !'),
                'time_from.required'       => __('Select Time Pleas !'),
            ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            'branch'                     =>'required',
            'position'                   =>'required',
            'name'                       =>'required',
            'password'                   =>'required|numeric',
            'confirm_password'           =>'required|numeric',
            'email'                      =>'required|unique:users,email',
        ];
    }

    public function messages()
    {
        return [
            'branch.required'             =>__('Select Branch Please !'),
            'position.required'           =>__('Select position Please !'),
            'name.required'               =>__('Enter Name Please !'),
            'password.numeric'            =>__('Password Number Only !'),
            'password.required'           =>__('Enter Password Please !'),
            'confirm_password.numeric'    =>__('Password Number Only !'),
            'confirm_password.required'   =>__('Enter Confirm Password Please !'),
            'email.required'              =>__('Enter Email Please !'),
            'email.unique'                =>__('The Email Already Exists. !'),
        ];
    }
}

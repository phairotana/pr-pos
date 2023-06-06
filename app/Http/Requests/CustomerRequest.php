<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->id) {
            $unique = ',' . request()->id;
        } else {
            $unique = '';
        }
        return [
            'customer_code' => 'required|string|unique:customers,customer_code' . $unique,
            'customer_name' => 'required|string',
            'customer_gender' => 'required|string',
            'customer_phone' => 'required',
            'branch_id' => 'required'
            // 'customer_group' => 'required'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}

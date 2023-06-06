<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
        if(request()->payment_status == 'Partial'){
            $creditDayValidate = 'required|numeric';
            $receivedAmountValidate = 'required|numeric';
        }
        if(request()->payment_status == 'Pending'){
            $creditDayValidate = 'required|numeric';
        }
        return [
            'customer_id' => 'required|integer',
            'branch' => 'required|integer',
            'payment_status' => 'required|string',
            'invoice_status' => 'required',
            'credit_day' => $creditDayValidate ?? '',
            'received_amount' => $receivedAmountValidate ?? ''
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

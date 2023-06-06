<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
        $invoice = Invoice::find(request()->reference_id);
        if(!empty($invoice)){
            $dueAmount = $invoice->due_amount;
        } else {
            $dueAmount =0;
        }
        return [
            'amount' => 'required|numeric|min:1|max:' . $dueAmount,
            'reference_id' => 'required|integer'
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
    
    private function unique($isUpdate = NULL)
    {
        if($isUpdate){
            Rule::unique('products')->where(function ($query) {
                $query->where('branch_id', $this->branch_id)
                   ->where('product_code', $this->product_code);
            })->ignore($isUpdate);
        }else{
            return Rule::unique('products')->where(function ($query) {
                $query->where('branch_id', $this->branch_id)
                   ->where('product_code', $this->product_code);
            });
        }
    }
}

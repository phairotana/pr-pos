<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        return [
            'product_code' => 'required|unique:products,product_code,'.request()->id,
            'product_name' => 'required|string',
            'category'     => 'required|integer',
            'sell_price'   => 'required|numeric',
            'cost_price'   => 'required|numeric',
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
                // $query->where('branch_id', $this->branch_id)
                $query->where('product_code', $this->product_code);
            })->ignore($isUpdate);
        }else{
            return Rule::unique('products')->where(function ($query) {
                // $query->where('branch_id', $this->branch_id)
                $query->where('product_code', $this->product_code);
            });
        }
    }
}

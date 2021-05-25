<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCashRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): Array
    {
        return [
            'type' => 'in:currency,bills|required',
            'denomination' => 'in:100000,50000,20000,10000,5000,1000,500,200,100,50|required|integer',
            'quantity' => 'required|int|min:1'
        ];
    }
}

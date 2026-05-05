<?php

namespace App\Http\Requests;

// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawalStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_ballance_id' => 'required|exists:store_ballances,id',
            'amount' => 'required|min:0|integer',
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|string',
            'bank_name' => 'required|string|in:BRI,BNI,BCA,Mandiri'
        ];
    }
}

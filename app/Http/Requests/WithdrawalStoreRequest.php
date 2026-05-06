<?php

namespace App\Http\Requests;

// use Illuminate\Contracts\Validation\ValidationRule;

use App\Models\StoreBallance;
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
            'amount' => [
                'required',
                'numeric',
                'min:50000',
                function ($attribute, $value, $fail) {
                $storeBallance = StoreBallance::find($this->store_ballance_id);
                if ($storeBallance->balance < $value) {
                    $fail('your balance is not enough');
                }
            }
            ],
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|string',
            'bank_name' => 'required|string|in:bri,bni,bca,mandiri'
        ];
    }
}

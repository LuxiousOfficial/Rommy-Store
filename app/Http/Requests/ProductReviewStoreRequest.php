<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductReviewStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_id' => 'required|string|exists:transactions,id',
            'product_id' => 'required|string|exists:products,id',
            'rating' => 'required|integer|max:5|min:1',
            'review' => 'required|string'
        ];
    }
}

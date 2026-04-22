<?php

namespace App\Http\Requests;

use App\Models\ProductCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'product_category_id' => [
                'required',
                'exists:product_categories,id',
                function ($attributes, $value, $fail) {
                    $category = ProductCategory::find($value);
                    if($category && $category->parent_id === null) {
                        $fail('Category product must have a parent category');
                    }
                }
            ],
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'condition' => 'required|string|in:new,second',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'product_images' => 'nullable|array',
            'product_images.*.image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'product_images.*.is_thumbnail' => 'required|boolean',
        ];
    }
}

<?php

namespace App\Http\Requests;

// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'profile_picture' => 'required|image|mimes:png,jpg',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:store,buyer',
        ];
    }
}

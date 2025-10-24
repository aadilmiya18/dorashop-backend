<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'price' => 'required|numeric'

//            'items' => 'required|array|min:1',
//            'items.*.product_id' => 'required|integer|exists:products,id',
//            'items.*.quantity' => 'nullable|integer|min:1',
//            'items.*.price' => 'required|numeric|min:0'
        ];
    }
}

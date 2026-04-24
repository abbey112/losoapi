<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'stock_adjustment' => 'sometimes|integer',
            'status' => 'sometimes|required|in:active,inactive',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('stock_adjustment') && !$this->has('stock_quantity')) {
                $validator->errors()->add('stock_quantity', 'The stock_quantity field is required when stock_adjustment is present.');
            }
        });
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'sku' => [
                'required',
                'string',
                'min:4',
                'max:30',
                'regex:/^[A-Z0-9_-]+$/i', 
                'unique:products,sku',
            ],
            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'sale_price' => [
                'required',
                'numeric',
                'min:0',
                'gte:purchase_price',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
                'lte:stock',
            ],
            'has_tax' => [
                'required',
                'boolean',
            ],
            'tax_percentage' => [
                'sometimes',
                'numeric',
                'between:0,100',
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'brand_id' => [
                'required',
                'exists:brands,id',
            ],
            'supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],
        ];
    }
}

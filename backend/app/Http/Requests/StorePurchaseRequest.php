<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            'supplier_id' => [
                'required',
                'integer',
                'exists:suppliers,id'
            ],
            'purchase_date' => [
                'required',
                'date',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'details' => [
                'required',
                'array',
                'min:1',
            ],
            'details.*.product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'details.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'details.*.unit_price' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ];
    }
}

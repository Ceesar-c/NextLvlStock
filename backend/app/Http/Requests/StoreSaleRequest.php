<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleRequest extends FormRequest
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
            'customer_id' => [
                'required',
                'integer',
                Rule::exists('customers', 'id')->where('is_active', true),
            ],
            'sale_date' => [
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
                'distinct',
                'exists:products,id'
            ],
            'details.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
                'sometimes',
                'string',
                'max:50',
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($this->route('user')->id),
            ],
            'password' => [
                'sometimes',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
            'role_id' => [
                'sometimes',
                'integer',
                Rule::exists('roles', 'id')
                    ->where(function ($query) {
                        $query->where('is_active', true);
                    }),
            ],
        ];
    }
}

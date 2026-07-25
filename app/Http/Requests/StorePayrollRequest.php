<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'employee_name' => ['required', 'string', 'max:255'],
            'role_type' => ['required', 'string', 'max:255'],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'period' => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'status' => ['required', 'string', 'in:paid,pending'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'period.regex' => 'Format periode harus YYYY-MM (contoh: 2026-07).',
        ];
    }
}

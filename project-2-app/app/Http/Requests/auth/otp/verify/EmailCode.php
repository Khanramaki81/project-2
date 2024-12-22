<?php

namespace App\Http\Requests\auth\otp\verify;

use Illuminate\Foundation\Http\FormRequest;

class EmailCode extends FormRequest
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
            'login' => ['required', 'string','exists:users,email'],
            'code' => ['required', 'string','exists:otp_codes,code'],
        ];
    }
}

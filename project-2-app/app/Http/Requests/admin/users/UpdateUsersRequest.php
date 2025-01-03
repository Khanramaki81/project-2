<?php

namespace App\Http\Requests\admin\users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required','string', 'between:2,100'],
            "email" => ['required','string','email', 'max:100','unique:users'],
            "password" => ['required', 'string', 'confirmed', Password::defaults()],
            "phone" => ['required','string','min:10','unique:users','regex:/^09[0-9]{9}$/'],
        ];
    }
}

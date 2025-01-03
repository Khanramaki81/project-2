<?php

namespace App\Http\Requests\admin\users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateUpdateUsersRequest extends FormRequest
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
        if($this->user_id){
            $rules["name"] = ['required','string', 'between:2,100'];
            $rules["email"] = ['required','string','email', 'max:100', Rule::unique('users')->ignore($this->user_id->id),];
            $rules["password"] = ['required', 'string', 'confirmed', Password::defaults()];
            $rules["phone"]= ['required','string','min:10','regex:/^09[0-9]{9}$/', Rule::unique('users')->ignore($this->user_id->id)];
        }else{
            $rules["name"] = ['required','string', 'between:2,100'];
            $rules["email"] = ['required','string','email', 'max:100','unique:users'];
            $rules["password"] = ['required', 'string', 'confirmed', Password::defaults()];
            $rules["phone"]= ['required','string','min:10','unique:users','regex:/^09[0-9]{9}$/'];
        }
        return $rules;
    }
}

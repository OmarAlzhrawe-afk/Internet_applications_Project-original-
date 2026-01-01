<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'First_name' => 'required',
            'Last_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'password' => 'required',
            'agency_id' => 'nullable',
            'role' => 'nullable',
        ];
    }
    public function messages()
    {
        return parent::messages();
    }
}

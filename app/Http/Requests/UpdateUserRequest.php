<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'First_name' => 'nullable',
            'Last_name' => 'nullable',
            'email' => 'nullable',
            'phone_number' => 'nullable',
            'password' => 'nullable',
            'agency_id' => 'nullable',
            'role' => 'nullable',
        ];
    }
    public function messages()
    {
        return parent::messages();
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'First_name' => 'required|string|max:10|min:3|alpha',
            'Last_name' => 'required|string|max:10|min:3|alpha',
            'email' => 'required|email|max:50|min:10',
            'phone_number' => 'required|string|starts_with:09|digits:10',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
    public function messages()
    {
        return [
            // messages for First_name
            'first_name.required' => 'The first name field is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name may not be greater than 10 characters.',
            'first_name.min' => 'The first name must be at least 3 characters.',
            'first_name.alpha' => 'The first name may only contain letters.',
            // messages for Last_name
            'Last_name.required' => 'The last name field is required.',
            'Last_name.string' => 'The last name must be a string.',
            'Last_name.max' => 'The last name may not be greater than 10 characters.',
            'Last_name.min' => 'The last name must be at least 3 characters.',
            'Last_name.alpha' => 'The last name may only contain letters.',
            // messages for email validation error
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 50 characters.',
            'email.min' => 'The email must be at least 10 characters.',
            // messages for phone_number
            'phone_number.required' => 'The phone number field is required.',
            'string' => 'The phone number must be a string.',
            'phone_number.starts_with' => 'The phone number must start with 09.',
            'phone_number.digits' => 'The phone number must be exactly 10 digits.',
            // messages for password
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',


        ];
    }
    // public function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(
    //         response()->json([
    //             'success' => false,
    //             'message' => 'Validation errors',
    //             'errors' => $validator->errors()
    //         ], 422)
    //     );
    // }
}

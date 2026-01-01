<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;

class SendVerficationCodeRequest extends FormRequest
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
            'type' => 'required|in:phone,email',
            'id' => 'required|exists:users,id',
        ];
    }
    public function messages()
    {
        return [
            'type.required' => 'The type field is required.',
            'type.in' => 'The type must be either phone or email.',
            'id.required' => 'The id field is required.',
            'id.exists' => 'The specified user does not exist.',
        ];
    }
}

<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintCommentRequest extends FormRequest
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
            'message' => 'required|string',
            'is_internal' => 'required | boolean',
            'complaint_id' => 'required|exists:complaints,id',
        ];
    }
}

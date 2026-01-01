<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintFileRequest extends FormRequest
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
            'complaint_id' => 'required|exists:complaints,id',
            'description'  => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,xls,mp4,mp3,txt|max:20480'
        ];
    }
}

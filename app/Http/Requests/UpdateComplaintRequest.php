<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
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
            'id' => 'required |exists:complaints,id',
            'title' => 'nullable',
            'description' => 'nullable',
            'type' => 'nullable|in:type1,type2,type3', // [ "خدمة",'سلوك' , "بنية تحتية"]
            'priority' => 'nullable|in:high,low,medium', //['high', 'low', 'medium']
            'status' => 'nullable', // ['new', 'in_review', 'in_progress', 'awaiting_info', 'resolved', 'rejected', 'closed']
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'address_text' => 'nullable',
        ];
    }
}

<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string|alpha|max:50',
            'description' => 'required|string|alpha|max:555',
            'type' => 'required|in:type1,type2,type3',
            'priority' => 'required|in:high,low,medium',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'address_text' => 'nullable|string|alpha|max:500',
            'agency_id' => 'required|exists:government_agencies,id',
        ];
    }
}

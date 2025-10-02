<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CourseStatus;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:' . implode(',', CourseStatus::values()),
            'is_premium' => 'sometimes|required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'title.max' => 'The course title may not be greater than 255 characters.',
            'status.required' => 'The course status is required.',
            'status.in' => 'The selected status is invalid. Must be Published or Pending.',
            'is_premium.required' => 'The premium status is required.',
            'is_premium.boolean' => 'The premium status must be true or false.',
        ];
    }
}

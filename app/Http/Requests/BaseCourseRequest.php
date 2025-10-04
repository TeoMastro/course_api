<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Enums\CourseStatus;

abstract class BaseCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_array($this->all()) && isset($this->all()[0])) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => $this->getArrayRejectionMessage(),
                    'error' => $this->getArrayRejectionError()
                ], 422)
            );
        }
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

    protected function getCourseValidationRules(): array
    {
        return [
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:' . implode(',', CourseStatus::values()),
            'is_premium' => 'boolean',
        ];
    }

    abstract protected function getArrayRejectionMessage(): string;
    abstract protected function getArrayRejectionError(): string;
}

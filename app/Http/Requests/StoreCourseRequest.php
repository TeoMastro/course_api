<?php

namespace App\Http\Requests;

class StoreCourseRequest extends BaseCourseRequest
{
    public function rules(): array
    {
        $baseRules = $this->getCourseValidationRules();
        
        return [
            'title' => 'required|' . $baseRules['title'],
            'description' => $baseRules['description'],
            'status' => 'required|' . $baseRules['status'],
            'is_premium' => 'required|' . $baseRules['is_premium'],
        ];
    }

    protected function getArrayRejectionMessage(): string
    {
        return 'This endpoint accepts only a single course object. To create multiple courses, send individual requests.';
    }

    protected function getArrayRejectionError(): string
    {
        return 'Array of courses not supported for creation.';
    }
}

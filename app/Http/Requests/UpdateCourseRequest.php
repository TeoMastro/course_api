<?php

namespace App\Http\Requests;

class UpdateCourseRequest extends BaseCourseRequest
{
    public function rules(): array
    {
        $baseRules = $this->getCourseValidationRules();
        
        return [
            'title' => 'sometimes|required|' . $baseRules['title'],
            'description' => $baseRules['description'],
            'status' => 'sometimes|required|' . $baseRules['status'],
            'is_premium' => 'sometimes|required|' . $baseRules['is_premium'],
        ];
    }

    protected function getArrayRejectionMessage(): string
    {
        return 'This endpoint accepts only a single course object for updates. To update multiple courses, send individual requests.';
    }

    protected function getArrayRejectionError(): string
    {
        return 'Array of courses not supported for updates.';
    }
}

<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function getAllCourses(int $perPage = Course::DEFAULT_PAGINATION_SIZE): LengthAwarePaginator
    {
        return $this->courseRepository->paginate($perPage);
    }

    public function getCourseById(int $id): ?Course
    {
        return $this->courseRepository->findById($id);
    }

    public function createCourse(array $data): Course
    {
        return $this->courseRepository->create($data);
    }

    public function updateCourse(int $id, array $data): ?Course
    {
        $course = $this->courseRepository->findById($id);
        
        if (!$course) {
            return null;
        }

        return $this->courseRepository->update($course, $data);
    }

    public function deleteCourse(int $id): bool
    {
        $course = $this->courseRepository->findById($id);
        
        if (!$course) {
            return false;
        }

        return $this->courseRepository->softDelete($course);
    }
}
<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
    public function paginate(int $perPage = Course::DEFAULT_PAGINATION_SIZE): LengthAwarePaginator
    {
        return Course::withoutTrashed()->paginate($perPage);
    }

    public function findById(int $id): ?Course
    {
        return Course::withoutTrashed()->find($id);
    }

    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data): Course
    {
        $course->update($data);
        return $course->fresh();
    }

    public function softDelete(Course $course): bool
    {
        return $course->delete();
    }
}
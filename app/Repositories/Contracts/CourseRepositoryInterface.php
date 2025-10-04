<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;

interface CourseRepositoryInterface
{
    public function paginate(int $perPage = Course::DEFAULT_PAGINATION_SIZE): LengthAwarePaginator;
    public function findById(int $id): ?Course;
    public function create(array $data): Course;
    public function update(Course $course, array $data): Course;
    public function softDelete(Course $course): bool;
}
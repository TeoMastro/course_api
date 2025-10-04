<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function __construct(
        private CourseService $courseService
    ) {}

    /**
     * Display a listing of courses.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', Course::DEFAULT_PAGINATION_SIZE);
            $courses = $this->courseService->getAllCourses($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $courses->items(),
                'meta' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created course.
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        try {
            $course = $this->courseService->createCourse($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => $course
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified course.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $course = $this->courseService->getCourseById($id);
            
            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $course
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified course.
     */
    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        try {
            $course = $this->courseService->updateCourse($id, $request->validated());
            
            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => $course
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified course (soft delete).
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->courseService->deleteCourse($id);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete course',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
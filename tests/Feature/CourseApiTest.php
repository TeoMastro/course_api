<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Enums\CourseStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_courses_with_pagination()
    {
        Course::factory()->count(25)->create();

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'title', 'description', 'status', 'is_premium', 'created_at', 'updated_at']
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total', 'from', 'to']
            ])
            ->assertJson([
                'success' => true,
            ]);

        $this->assertCount(Course::DEFAULT_PAGINATION_SIZE, $response->json('data'));
        $this->assertEquals(25, $response->json('meta.total'));
    }

    /** @test */
    public function it_can_create_a_course()
    {
        $courseData = [
            'title' => 'Laravel Fundamentals',
            'description' => 'Learn the basics of Laravel',
            'status' => 'Published',
            'is_premium' => false,
        ];

        $response = $this->postJson('/api/courses', $courseData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Course created successfully',
                'data' => [
                    'title' => 'Laravel Fundamentals',
                    'description' => 'Learn the basics of Laravel',
                    'status' => 'Published',
                    'is_premium' => false,
                ]
            ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Laravel Fundamentals',
            'status' => 'Published',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_course()
    {
        $response = $this->postJson('/api/courses', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'status', 'is_premium']);
    }

    /** @test */
    public function it_validates_status_enum_when_creating_course()
    {
        $courseData = [
            'title' => 'Test Course',
            'status' => 'InvalidStatus',
            'is_premium' => false,
        ];

        $response = $this->postJson('/api/courses', $courseData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    /** @test */
    public function it_can_show_a_course()
    {
        $course = Course::factory()->create([
            'title' => 'Test Course',
            'description' => 'Test Description',
        ]);

        $response = $this->getJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $course->id,
                    'title' => 'Test Course',
                    'description' => 'Test Description',
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_course_not_found()
    {
        $response = $this->getJson('/api/courses/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Course not found',
            ]);
    }

    /** @test */
    public function it_can_update_a_course()
    {
        $course = Course::factory()->create([
            'title' => 'Original Title',
            'status' => 'Pending',
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'status' => 'Published',
        ];

        $response = $this->putJson("/api/courses/{$course->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => [
                    'title' => 'Updated Title',
                    'status' => 'Published',
                ]
            ]);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated Title',
            'status' => 'Published',
        ]);
    }

    /** @test */
    public function it_can_soft_delete_a_course()
    {
        $course = Course::factory()->create();

        $response = $this->deleteJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Course deleted successfully',
            ]);

        $this->assertSoftDeleted('courses', [
            'id' => $course->id,
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_course()
    {
        $response = $this->deleteJson('/api/courses/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Course not found',
            ]);
    }

    /** @test */
    public function it_rejects_array_of_courses_when_creating()
    {
        $coursesArray = [
            [
                'title' => 'Course 1',
                'description' => 'Description 1',
                'status' => 'Published',
                'is_premium' => false,
            ],
            [
                'title' => 'Course 2', 
                'description' => 'Description 2',
                'status' => 'Pending',
                'is_premium' => true,
            ]
        ];

        $response = $this->postJson('/api/courses', $coursesArray);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'This endpoint accepts only a single course object. To create multiple courses, send individual requests.',
                'error' => 'Array of courses not supported for creation.'
            ]);

        $this->assertDatabaseCount('courses', 0);
    }

    /** @test */
    public function it_rejects_array_of_courses_when_updating()
    {
        $course = Course::factory()->create();
        
        $coursesArray = [
            [
                'title' => 'Updated Course 1',
                'status' => 'Published',
            ],
            [
                'title' => 'Updated Course 2',
                'status' => 'Pending',
            ]
        ];

        $response = $this->putJson("/api/courses/{$course->id}", $coursesArray);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'This endpoint accepts only a single course object for updates. To update multiple courses, send individual requests.',  
                'error' => 'Array of courses not supported for updates.'
            ]);

        $course->refresh();
        $this->assertNotEquals('Updated Course 1', $course->title);
    }
}
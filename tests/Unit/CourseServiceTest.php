<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Services\CourseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class CourseServiceTest extends TestCase
{
    private CourseRepositoryInterface $repository;
    private CourseService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = Mockery::mock(CourseRepositoryInterface::class);
        $this->service = new CourseService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_all_courses()
    {
        $paginator = Mockery::mock(LengthAwarePaginator::class);
        $this->repository->shouldReceive('paginate')
            ->once()
            ->with(Course::DEFAULT_PAGINATION_SIZE)
            ->andReturn($paginator);

        $result = $this->service->getAllCourses(Course::DEFAULT_PAGINATION_SIZE);

        $this->assertSame($paginator, $result);
    }

    /** @test */
    public function it_can_get_course_by_id()
    {
        $course = new Course(['id' => 1, 'title' => 'Test Course']);
        $this->repository->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($course);

        $result = $this->service->getCourseById(1);

        $this->assertSame($course, $result);
    }

    /** @test */
    public function it_can_create_course()
    {
        $data = ['title' => 'New Course', 'status' => 'Published', 'is_premium' => false];
        $course = new Course($data);
        
        $this->repository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($course);

        $result = $this->service->createCourse($data);

        $this->assertSame($course, $result);
    }

    /** @test */
    public function it_can_update_course()
    {
        $course = new Course(['id' => 1, 'title' => 'Original']);
        $updateData = ['title' => 'Updated'];
        $updatedCourse = new Course(['id' => 1, 'title' => 'Updated']);

        $this->repository->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($course);

        $this->repository->shouldReceive('update')
            ->once()
            ->with($course, $updateData)
            ->andReturn($updatedCourse);

        $result = $this->service->updateCourse(1, $updateData);

        $this->assertSame($updatedCourse, $result);
    }

    /** @test */
    public function it_returns_null_when_updating_non_existent_course()
    {
        $this->repository->shouldReceive('findById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $result = $this->service->updateCourse(999, ['title' => 'Updated']);

        $this->assertNull($result);
    }

    /** @test */
    public function it_can_delete_course()
    {
        $course = new Course(['id' => 1]);
        
        $this->repository->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($course);

        $this->repository->shouldReceive('softDelete')
            ->once()
            ->with($course)
            ->andReturn(true);

        $result = $this->service->deleteCourse(1);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_returns_false_when_deleting_non_existent_course()
    {
        $this->repository->shouldReceive('findById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $result = $this->service->deleteCourse(999);

        $this->assertFalse($result);
    }
}
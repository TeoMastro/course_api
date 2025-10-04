<?php

namespace Database\Factories;

use App\Enums\CourseStatus;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(CourseStatus::values()),
            'is_premium' => fake()->boolean(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CourseStatus::PUBLISHED->value,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CourseStatus::PENDING->value,
        ]);
    }

    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_premium' => true,
        ]);
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_premium' => false,
        ]);
    }
}
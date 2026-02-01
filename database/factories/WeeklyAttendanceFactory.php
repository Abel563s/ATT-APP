<?php

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\Department;
use App\Models\User;
use App\Models\WeeklyAttendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeeklyAttendance>
 */
class WeeklyAttendanceFactory extends Factory
{
    protected $model = WeeklyAttendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'week_start_date' => Carbon::now()->startOfWeek(Carbon::MONDAY),
            'status' => AttendanceStatus::DRAFT,
            'submitted_by' => null,
            'approved_by' => null,
            'rejection_reason' => null,
        ];
    }

    /**
     * Indicate that the attendance is pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => User::factory(),
        ]);
    }

    /**
     * Indicate that the attendance is approved.
     */
    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => AttendanceStatus::APPROVED,
            'submitted_by' => User::factory(),
            'approved_by' => User::factory(),
        ]);
    }

    /**
     * Indicate that the attendance is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => AttendanceStatus::REJECTED,
            'submitted_by' => User::factory(),
            'rejection_reason' => fake()->sentence(),
        ]);
    }
}

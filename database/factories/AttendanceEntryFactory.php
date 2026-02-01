<?php

namespace Database\Factories;

use App\Models\AttendanceEntry;
use App\Models\Employee;
use App\Models\WeeklyAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceEntry>
 */
class AttendanceEntryFactory extends Factory
{
    protected $model = AttendanceEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $codes = ['P', 'A', 'L', 'H', 'S'];

        return [
            'weekly_attendance_id' => WeeklyAttendance::factory(),
            'employee_id' => Employee::factory(),
            'mon_m' => fake()->randomElement($codes),
            'mon_a' => fake()->randomElement($codes),
            'tue_m' => fake()->randomElement($codes),
            'tue_a' => fake()->randomElement($codes),
            'wed_m' => fake()->randomElement($codes),
            'wed_a' => fake()->randomElement($codes),
            'thu_m' => fake()->randomElement($codes),
            'thu_a' => fake()->randomElement($codes),
            'fri_m' => fake()->randomElement($codes),
            'fri_a' => fake()->randomElement($codes),
            'sat_m' => fake()->randomElement($codes),
            'sat_a' => fake()->randomElement($codes),
        ];
    }

    /**
     * All present attendance.
     */
    public function allPresent(): static
    {
        return $this->state(fn(array $attributes) => [
            'mon_m' => 'P',
            'mon_a' => 'P',
            'tue_m' => 'P',
            'tue_a' => 'P',
            'wed_m' => 'P',
            'wed_a' => 'P',
            'thu_m' => 'P',
            'thu_a' => 'P',
            'fri_m' => 'P',
            'fri_a' => 'P',
            'sat_m' => 'P',
            'sat_a' => 'P',
        ]);
    }
}

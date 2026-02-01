<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Models\ApprovalLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\WeeklyAttendance;
use App\Models\AttendanceEntry;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $manager1;
    protected User $manager2;
    protected User $deptUser1;
    protected User $deptUser2;
    protected Department $department1;
    protected Department $department2;
    protected Employee $employee1;
    protected Employee $employee2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin
        $this->admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);

        // Create Managers
        $this->manager1 = User::factory()->create([
            'name' => 'Manager One',
            'email' => 'manager1@test.com',
            'role' => 'manager',
        ]);

        $this->manager2 = User::factory()->create([
            'name' => 'Manager Two',
            'email' => 'manager2@test.com',
            'role' => 'manager',
        ]);

        // Create Departments
        $this->department1 = Department::factory()->create([
            'name' => 'IT Department',
            'code' => 'IT001',
            'manager_id' => $this->manager1->id,
            'is_active' => true,
        ]);

        $this->department2 = Department::factory()->create([
            'name' => 'HR Department',
            'code' => 'HR001',
            'manager_id' => $this->manager2->id,
            'is_active' => true,
        ]);

        // Create Department Users
        $this->deptUser1 = User::factory()->create([
            'name' => 'IT User',
            'email' => 'ituser@test.com',
            'role' => 'user',
            'department_id' => $this->department1->id,
        ]);

        $this->deptUser2 = User::factory()->create([
            'name' => 'HR User',
            'email' => 'hruser@test.com',
            'role' => 'user',
            'department_id' => $this->department2->id,
        ]);

        // Create Employees
        $this->employee1 = Employee::factory()->create([
            'user_id' => $this->deptUser1->id,
            'department_id' => $this->department1->id,
            'employee_id' => 'EMP001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@test.com',
            'is_active' => true,
        ]);

        $this->employee2 = Employee::factory()->create([
            'user_id' => $this->deptUser2->id,
            'department_id' => $this->department2->id,
            'employee_id' => 'EMP002',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@test.com',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function department_user_can_save_attendance_as_draft()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $this->actingAs($this->deptUser1);

        $response = $this->post(route('attendance.store'), [
            'weekly_attendance_id' => WeeklyAttendance::factory()->create([
                'department_id' => $this->department1->id,
                'week_start_date' => $weekStart,
                'status' => AttendanceStatus::DRAFT,
            ])->id,
            'attendance' => [
                $this->employee1->id => [
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
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Attendance saved as draft.');

        $this->assertDatabaseHas('attendance_entries', [
            'employee_id' => $this->employee1->id,
            'mon_m' => 'P',
            'tue_m' => 'P',
        ]);
    }

    /** @test */
    public function department_user_can_submit_attendance_for_approval()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $attendance = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::DRAFT,
        ]);

        // Create attendance entries
        AttendanceEntry::factory()->create([
            'weekly_attendance_id' => $attendance->id,
            'employee_id' => $this->employee1->id,
            'mon_m' => 'P',
            'mon_a' => 'P',
        ]);

        $this->actingAs($this->deptUser1);

        $response = $this->post(route('attendance.submit', $attendance));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Attendance submitted for approval.');

        // Verify status changed to PENDING
        $this->assertDatabaseHas('weekly_attendances', [
            'id' => $attendance->id,
            'status' => AttendanceStatus::PENDING->value,
            'submitted_by' => $this->deptUser1->id,
        ]);

        // Verify approval log created
        $this->assertDatabaseHas('approval_logs', [
            'weekly_attendance_id' => $attendance->id,
            'user_id' => $this->deptUser1->id,
            'action' => 'submitted',
        ]);
    }

    /** @test */
    public function manager_can_see_pending_approvals_for_their_department()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        // Create pending attendance for manager1's department
        $attendance1 = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => $this->deptUser1->id,
        ]);

        // Create pending attendance for manager2's department
        $attendance2 = WeeklyAttendance::factory()->create([
            'department_id' => $this->department2->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => $this->deptUser2->id,
        ]);

        $this->actingAs($this->manager1);

        $response = $this->get(route('manager.approvals.index'));

        $response->assertStatus(200);
        $response->assertSee($this->department1->name);
        $response->assertDontSee($this->department2->name); // Should not see other manager's department
    }

    /** @test */
    public function admin_can_see_all_pending_approvals()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        // Create pending attendances for both departments
        $attendance1 = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => $this->deptUser1->id,
        ]);

        $attendance2 = WeeklyAttendance::factory()->create([
            'department_id' => $this->department2->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => $this->deptUser2->id,
        ]);

        $this->actingAs($this->admin);

        $response = $this->get(route('manager.approvals.index'));

        $response->assertStatus(200);
        $response->assertSee($this->department1->name);
        $response->assertSee($this->department2->name); // Admin should see all departments
    }

    /** @test */
    public function manager_can_approve_attendance_for_their_department()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $attendance = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => $this->deptUser1->id,
        ]);

        $this->actingAs($this->manager1);

        $response = $this->post(route('manager.approvals.approve', $attendance), [
            'comment' => 'Looks good!',
        ]);

        $response->assertRedirect(route('manager.approvals.index'));
        $response->assertSessionHas('success', 'Attendance approved successfully.');

        // Verify status changed to APPROVED
        $this->assertDatabaseHas('weekly_attendances', [
            'id' => $attendance->id,
            'status' => AttendanceStatus::APPROVED->value,
            'approved_by' => $this->manager1->id,
        ]);

        // Verify approval log created
        $this->assertDatabaseHas('approval_logs', [
            'weekly_attendance_id' => $attendance->id,
            'user_id' => $this->manager1->id,
            'action' => 'approved',
            'comment' => 'Looks good!',
        ]);
    }

    /** @test */
    public function admin_can_approve_attendance_for_any_department()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $attendance = WeeklyAttendance::factory()->create([
            'department_id' => $this->department2->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => $this->deptUser2->id,
        ]);

        $this->actingAs($this->admin);

        $response = $this->post(route('manager.approvals.approve', $attendance), [
            'comment' => 'Admin approval',
        ]);

        $response->assertRedirect(route('manager.approvals.index'));
        $response->assertSessionHas('success', 'Attendance approved successfully.');

        // Verify status changed to APPROVED
        $this->assertDatabaseHas('weekly_attendances', [
            'id' => $attendance->id,
            'status' => AttendanceStatus::APPROVED->value,
            'approved_by' => $this->admin->id,
        ]);

        // Verify approval log created
        $this->assertDatabaseHas('approval_logs', [
            'weekly_attendance_id' => $attendance->id,
            'user_id' => $this->admin->id,
            'action' => 'approved',
            'comment' => 'Admin approval',
        ]);
    }

    /** @test */
    public function manager_can_reject_attendance_with_reason()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $attendance = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => $this->deptUser1->id,
        ]);

        $this->actingAs($this->manager1);

        $response = $this->post(route('manager.approvals.reject', $attendance), [
            'comment' => 'Please correct the attendance for Tuesday.',
        ]);

        $response->assertRedirect(route('manager.approvals.index'));
        $response->assertSessionHas('success', 'Attendance rejected.');

        // Verify status changed to REJECTED
        $this->assertDatabaseHas('weekly_attendances', [
            'id' => $attendance->id,
            'status' => AttendanceStatus::REJECTED->value,
            'rejection_reason' => 'Please correct the attendance for Tuesday.',
        ]);

        // Verify approval log created
        $this->assertDatabaseHas('approval_logs', [
            'weekly_attendance_id' => $attendance->id,
            'user_id' => $this->manager1->id,
            'action' => 'rejected',
            'comment' => 'Please correct the attendance for Tuesday.',
        ]);
    }

    /** @test */
    public function rejected_attendance_can_be_resubmitted()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $attendance = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::REJECTED,
            'submitted_by' => $this->deptUser1->id,
            'rejection_reason' => 'Needs correction',
        ]);

        $this->actingAs($this->deptUser1);

        // User should be able to edit rejected attendance
        $this->assertTrue($attendance->isEditable());

        // Resubmit after corrections
        $response = $this->post(route('attendance.submit', $attendance));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Attendance submitted for approval.');

        // Verify status changed back to PENDING
        $this->assertDatabaseHas('weekly_attendances', [
            'id' => $attendance->id,
            'status' => AttendanceStatus::PENDING->value,
        ]);
    }

    /** @test */
    public function approved_attendance_cannot_be_edited()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $attendance = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::APPROVED,
            'submitted_by' => $this->deptUser1->id,
            'approved_by' => $this->manager1->id,
        ]);

        $this->actingAs($this->deptUser1);

        // Approved attendance should not be editable
        $this->assertFalse($attendance->isEditable());

        // Try to submit approved attendance
        $response = $this->post(route('attendance.submit', $attendance));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'This attendance record cannot be submitted.');
    }

    /** @test */
    public function approval_logs_track_complete_workflow()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $attendance = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::DRAFT,
        ]);

        // Step 1: Submit
        $this->actingAs($this->deptUser1);
        $this->post(route('attendance.submit', $attendance));

        // Step 2: Reject
        $this->actingAs($this->manager1);
        $this->post(route('manager.approvals.reject', $attendance->fresh()), [
            'comment' => 'First rejection',
        ]);

        // Step 3: Resubmit
        $this->actingAs($this->deptUser1);
        $this->post(route('attendance.submit', $attendance->fresh()));

        // Step 4: Approve
        $this->actingAs($this->manager1);
        $this->post(route('manager.approvals.approve', $attendance->fresh()), [
            'comment' => 'Final approval',
        ]);

        // Verify all logs exist
        $logs = ApprovalLog::where('weekly_attendance_id', $attendance->id)
            ->orderBy('created_at')
            ->get();

        $this->assertCount(4, $logs);
        $this->assertEquals('submitted', $logs[0]->action);
        $this->assertEquals('rejected', $logs[1]->action);
        $this->assertEquals('submitted', $logs[2]->action);
        $this->assertEquals('approved', $logs[3]->action);
    }

    /** @test */
    public function multiple_departments_can_submit_attendance_independently()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        // Department 1 submits
        $attendance1 = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::DRAFT,
        ]);

        $this->actingAs($this->deptUser1);
        $this->post(route('attendance.submit', $attendance1));

        // Department 2 submits
        $attendance2 = WeeklyAttendance::factory()->create([
            'department_id' => $this->department2->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::DRAFT,
        ]);

        $this->actingAs($this->deptUser2);
        $this->post(route('attendance.submit', $attendance2));

        // Both should be pending
        $this->assertDatabaseHas('weekly_attendances', [
            'id' => $attendance1->id,
            'status' => AttendanceStatus::PENDING->value,
            'department_id' => $this->department1->id,
        ]);

        $this->assertDatabaseHas('weekly_attendances', [
            'id' => $attendance2->id,
            'status' => AttendanceStatus::PENDING->value,
            'department_id' => $this->department2->id,
        ]);

        // Manager 1 should only see their department
        $this->actingAs($this->manager1);
        $response = $this->get(route('manager.approvals.index'));
        $response->assertSee($this->department1->name);
        $response->assertDontSee($this->department2->name);

        // Admin should see both
        $this->actingAs($this->admin);
        $response = $this->get(route('manager.approvals.index'));
        $response->assertSee($this->department1->name);
        $response->assertSee($this->department2->name);
    }

    /** @test */
    public function rejection_requires_comment()
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $attendance = WeeklyAttendance::factory()->create([
            'department_id' => $this->department1->id,
            'week_start_date' => $weekStart,
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => $this->deptUser1->id,
        ]);

        $this->actingAs($this->manager1);

        // Try to reject without comment
        $response = $this->post(route('manager.approvals.reject', $attendance), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors('comment');

        // Verify status did not change
        $this->assertDatabaseHas('weekly_attendances', [
            'id' => $attendance->id,
            'status' => AttendanceStatus::PENDING->value,
        ]);
    }
}

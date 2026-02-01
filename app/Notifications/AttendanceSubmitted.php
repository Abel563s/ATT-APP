<?php

namespace App\Notifications;

use App\Models\WeeklyAttendance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceSubmitted extends Notification
{
    use Queueable;

    protected $attendance;

    /**
     * Create a new notification instance.
     */
    public function __construct(WeeklyAttendance $attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Attendance Protocol Submitted')
            ->greeting('Hello, Manager')
            ->line('A new attendance protocol has been submitted for ' . $this->attendance->department->name . '.')
            ->line('Week Starting: ' . $this->attendance->week_start_date->format('M d, Y'))
            ->line('Submitted by: ' . $this->attendance->submitter->name)
            ->action('Review Details', route('manager.approvals.show', $this->attendance))
            ->line('Please review and proceed with the necessary authorization.');
    }

    /**
     * Get the array representation of the notification (for database).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance_submitted',
            'title' => 'Attendance Protocol Submitted',
            'message' => 'New protocol submitted for ' . $this->attendance->department->name,
            'attendance_id' => $this->attendance->id,
            'department_name' => $this->attendance->department->name,
            'submitter_name' => $this->attendance->submitter->name,
            'week_date' => $this->attendance->week_start_date->format('M d, Y'),
            'action_url' => route('manager.approvals.show', $this->attendance),
        ];
    }
}

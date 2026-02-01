<?php

namespace App\Notifications;

use App\Models\WeeklyAttendance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceStatusUpdated extends Notification
{
    use Queueable;

    protected $attendance;
    protected $oldStatus;
    protected $newStatusLabel;

    /**
     * Create a new notification instance.
     */
    public function __construct(WeeklyAttendance $attendance)
    {
        $this->attendance = $attendance;
        $this->newStatusLabel = $attendance->status->label();
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
        $status = strtoupper($this->newStatusLabel);
        return (new MailMessage)
            ->subject('Attendance Protocol Status Update')
            ->line('The status of an attendance protocol has been updated.')
            ->line('Department: ' . $this->attendance->department->name)
            ->line('Week Starting: ' . $this->attendance->week_start_date->format('M d, Y'))
            ->line('New Status: ' . $status)
            ->action('View Record', route('manager.approvals.show', $this->attendance))
            ->line('System synchronization completed.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'status_updated',
            'title' => 'Protocol Status Update',
            'message' => 'Attendance for ' . $this->attendance->department->name . ' is now ' . $this->newStatusLabel,
            'attendance_id' => $this->attendance->id,
            'status' => $this->newStatusLabel,
            'action_url' => route('manager.approvals.show', $this->attendance),
        ];
    }
}

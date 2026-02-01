<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeCreated extends Notification
{
    use Queueable;

    protected $employee;

    /**
     * Create a new notification instance.
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
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
            ->subject('New Personnel Node Initialization')
            ->line('A new personnel node has been initialized in the Staff Registry.')
            ->line('Name: ' . $this->employee->full_name)
            ->line('ID: ' . $this->employee->employee_id)
            ->line('Division: ' . $this->employee->department->name)
            ->action('Manage Asset', route('admin.employees.show', $this->employee))
            ->line('Identity clearance pending.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'employee_created',
            'title' => 'New Asset Initialized',
            'message' => $this->employee->full_name . ' (' . $this->employee->employee_id . ') added to ' . $this->employee->department->name,
            'employee_id' => $this->employee->id,
            'employee_code' => $this->employee->employee_id,
            'action_url' => route('admin.employees.show', $this->employee),
        ];
    }
}

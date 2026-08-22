<?php

namespace App\Notifications;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use App\Channels\FcmChannel;
use Illuminate\Notifications\Notification;

class AttendanceRecordedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Attendance $attendance
    ) {
    }

    public function via(object $notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'attendance_recorded',

            'title' => 'Attendance Recorded',

            'message' => sprintf(
                'Your attendance was recorded as %s on %s.',
                $this->attendance->status,
                $this->attendance->date
            ),

            'attendance_id' => $this->attendance->id,

            'date' => $this->attendance->date,

            'status' => $this->attendance->status,

            'enrollment_id' => $this->attendance->enrollment_id,

            'section_id' => $this->attendance->enrollment->section_id,
        ];
    }
}
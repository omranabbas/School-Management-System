<?php

namespace App\Notifications;

use App\Models\TeacherAbsence;
use Illuminate\Bus\Queueable;
use App\Channels\FcmChannel;
use Illuminate\Notifications\Notification;

class TeacherAbsenceStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TeacherAbsence $absence
    ) {
    }

    public function via(object $notifiable): array
    {
        return [
            'database',
            FcmChannel::class,
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        $status = $this->absence->status;

        return [
            'type' => 'teacher_absence_status_updated',

            'title' => $status === 'approved'
                ? 'Absence Request Approved'
                : 'Absence Request Rejected',

            'message' => $status === 'approved'
                ? 'Your absence request has been approved.'
                : 'Your absence request has been rejected.',

            'teacher_absence_id' => $this->absence->id,

            'status' => $status,

            'absence_date' => $this->absence->absence_date,

            'reason' => $this->absence->reason,

            'replacement_teacher_id' => $this->absence->replacement_teacher_id,
        ];
    }
}
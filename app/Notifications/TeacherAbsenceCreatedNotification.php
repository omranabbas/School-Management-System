<?php

namespace App\Notifications;

use App\Models\TeacherAbsence;
use Illuminate\Bus\Queueable;
use App\Channels\FcmChannel;
use Illuminate\Notifications\Notification;

class TeacherAbsenceCreatedNotification extends Notification
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
        return [
            'type' => 'teacher_absence_created',

            'title' => 'Teacher Absence Created',

            'message' => sprintf(
                'Your absence request for %s has been created.',
                $this->absence->absence_date
            ),

            'teacher_absence_id' => $this->absence->id,

            'teacher_id' => $this->absence->teacher_id,

            'absence_date' => $this->absence->absence_date,

            'reason' => $this->absence->reason,

            'status' => $this->absence->status,

            'replacement_teacher_id' => $this->absence->replacement_teacher_id,
        ];
    }
}
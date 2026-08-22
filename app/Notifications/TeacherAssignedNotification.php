<?php

namespace App\Notifications;

use App\Models\TeacherSubject;
use Illuminate\Bus\Queueable;
use App\Channels\FcmChannel;
use Illuminate\Notifications\Notification;

class TeacherAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TeacherSubject $teacherSubject
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
            'type' => 'teacher_assigned',

            'title' => 'New Teaching Assignment',

            'message' => sprintf(
                'You have been assigned to teach %s.',
                $this->teacherSubject->subject->name
            ),

            'teacher_subject_id' => $this->teacherSubject->id,

            'subject_id' => $this->teacherSubject->subject_id,

            'subject_name' => $this->teacherSubject->subject->name,

            'section_id' => $this->teacherSubject->section_id,

            'grade_id' => $this->teacherSubject->section->grade_id,

            'academic_year_id' => $this->teacherSubject->academic_year_id,
        ];
    }
}
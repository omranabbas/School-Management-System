<?php

namespace App\Notifications;

use App\Models\Mark;
use Illuminate\Bus\Queueable;
use App\Channels\FcmChannel;
use Illuminate\Notifications\Notification;

class MarkUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Mark $mark
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
            'type' => 'mark_updated',

            'title' => 'Mark Updated',

            'message' => sprintf(
                'Your mark for %s has been updated.',
                $this->mark->teacherSubject->subject->name
            ),

            'mark_id' => $this->mark->id,

            'subject_id' => $this->mark->teacherSubject->subject_id,

            'subject_name' => $this->mark->teacherSubject->subject->name,

            'score' => $this->mark->score,

            'max_score' => $this->mark->max_score,

            'term' => $this->mark->term,

            'type_name' => $this->mark->type,

            'exam_date' => $this->mark->exam_date,
        ];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduleOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_absence_id',
        'schedule_id',
        'date',
        'status',
        'replacement_teacher_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function teacherAbsence()
    {
        return $this->belongsTo(TeacherAbsence::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function replacementTeacher()
    {
        return $this->belongsTo(User::class, 'replacement_teacher_id');
    }
}
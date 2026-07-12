<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_subject_id',
        'day',
        'period',
        'start_time',
        'end_time'
    ];

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class);
    }

    public function overrides()
    {
        return $this->hasMany(ScheduleOverride::class);
    }
}

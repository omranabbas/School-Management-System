<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherAbsence extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'absence_date',
        'reason',
        'replacement_teacher_id',
        'status',
    ];

    protected $casts = [
        'absence_date' => 'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function replacementTeacher()
    {
        return $this->belongsTo(User::class, 'replacement_teacher_id');
    }
    public function scheduleOverrides()
    {
        return $this->hasMany(ScheduleOverride::class);
    }
}
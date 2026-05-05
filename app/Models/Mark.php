<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'teacher_subject_id',
        'score',
        'max_score',
        'term',
        'type',
        'exam_date'
    ];

    public function enrollment()
    {
        return $this->belongsTo(StudentEnrollment::class);
    }

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class);
    }
}

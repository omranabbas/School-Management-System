<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'section_id',
        'academic_year_id'
    ];
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

     public function attendances()
    {
        return $this->hasMany(Attendance::class, 'enrollment_id');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class, 'enrollment_id');
    }
}

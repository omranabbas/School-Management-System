<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'personal_image',
        'phone',
        'parent_phone'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}

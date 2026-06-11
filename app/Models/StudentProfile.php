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

    protected $appends = [
        'personal_image_url',
    ];

    public function getPersonalImageUrlAttribute()
    {
        return $this->personal_image
            ? asset('storage/' . $this->personal_image)
            : null;
    }
}

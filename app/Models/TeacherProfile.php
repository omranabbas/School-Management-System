<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'years_experience',
        'certificate_image'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

     protected $appends = [
        'certificate_image_url',
    ];


    public function getCertificateImageUrlAttribute()
    {
        return $this->certificate_image
            ? asset('storage/' . $this->certificate_image)
            : null;
    }
}

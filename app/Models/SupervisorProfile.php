<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorProfile extends Model
{
     use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'years_experience',
        'certificate_image',
        'stage'
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
